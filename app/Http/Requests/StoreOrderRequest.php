<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'phone' => ['required', 'regex:/^01[3-9][0-9]{8}$/'],
            'email' => ['nullable', 'email:rfc', 'max:255'],
            'address' => ['required', 'string', 'min:10', 'max:500'],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'আপনার নাম লিখুন।',
            'name.min' => 'নাম অন্তত ২ অক্ষরের হতে হবে।',
            'phone.required' => 'আপনার ফোন নম্বর লিখুন।',
            'phone.regex' => 'সঠিক বাংলাদেশি ফোন নম্বর দিন।',
            'email.email' => 'সঠিক ইমেইল ঠিকানা দিন।',
            'address.required' => 'সম্পূর্ণ ডেলিভারি ঠিকানা লিখুন।',
            'address.min' => 'ঠিকানাটি আরও বিস্তারিতভাবে লিখুন।',
            'quantity.integer' => 'সঠিক পরিমাণ লিখুন।',
            'quantity.min' => 'পরিমাণ কমপক্ষে ১ হতে হবে।',
        ];
    }

    protected function prepareForValidation(): void
    {
        $banglaDigits = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
        $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
        $phone = str_replace($banglaDigits, $englishDigits, (string) $this->input('phone'));
        $phone = preg_replace('/[^0-9]/', '', $phone) ?? '';

        if (str_starts_with($phone, '88') && strlen($phone) === 13) {
            $phone = substr($phone, 2);
        }

        $this->merge([
            'name' => trim((string) $this->input('name')),
            'phone' => $phone,
            'email' => strtolower(trim((string) $this->input('email'))) ?: null,
            'address' => trim((string) $this->input('address')),
        ]);
    }

    protected function failedValidation(Validator $validator): void
    {
        if ($this->expectsJson() || $this->ajax()) {
            throw new HttpResponseException(response()->json([
                'message' => 'দেওয়া তথ্যগুলো যাচাই করুন।',
                'errors' => $validator->errors()->messages(),
            ], 422));
        }

        parent::failedValidation($validator);
    }
}
