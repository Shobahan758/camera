<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>অ্যাডমিন লগইন | দৃশ্যপ্রো</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 24px; font-family: 'Hind Siliguri', Arial, sans-serif; background: radial-gradient(circle at 80% 20%, rgba(14,165,233,.16), transparent 28%), linear-gradient(135deg, #f5f7ff, #eef2ff); color: #090d1a; }
        .card { width: 100%; max-width: 430px; padding: 38px; background: #fff; border: 1px solid #e2e8f0; border-radius: 22px; box-shadow: 0 18px 50px rgba(67, 56, 202, .14); }
        .brand { margin-bottom: 8px; color: #4338ca; font-size: 30px; font-weight: 800; text-align: center; }
        .subtitle { margin: 0 0 28px; color: #667085; text-align: center; }
        label { display: block; margin: 0 0 8px; font-weight: 700; }
        input[type=email], input[type=password] { width: 100%; padding: 13px 14px; border: 1px solid #dbe3ee; border-radius: 10px; font-size: 16px; outline: none; }
        input:focus { border-color: #635bff; box-shadow: 0 0 0 3px rgba(99,91,255,.12); }
        .field { margin-bottom: 20px; }
        .error { margin: 7px 0 0; color: #c62828; font-size: 14px; }
        .remember { display: flex; gap: 8px; align-items: center; margin-bottom: 22px; color: #667085; }
        button { width: 100%; padding: 14px; border: 0; border-radius: 10px; background: linear-gradient(120deg,#4338ca,#635bff,#0ea5e9); color: white; font-size: 16px; font-weight: 700; cursor: pointer; }
        button:hover { background: linear-gradient(120deg,#3730a3,#4338ca,#0284c7); }
        .home { display: block; margin-top: 22px; color: #4338ca; text-align: center; text-decoration: none; }
    </style>
</head>
<body>
    <main class="card">
        <div class="brand">দৃশ্যপ্রো</div>
        <p class="subtitle">অ্যাডমিন প্যানেলে লগইন করুন</p>

        <form method="POST" action="{{ route('login.store') }}">
            @csrf
            <div class="field">
                <label for="email">ইমেইল</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" autofocus required>
                @error('email') <p class="error">{{ $message }}</p> @enderror
            </div>
            <div class="field">
                <label for="password">পাসওয়ার্ড</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required>
                @error('password') <p class="error">{{ $message }}</p> @enderror
            </div>
            <label class="remember"><input type="checkbox" name="remember" value="1"> আমাকে মনে রাখুন</label>
            <button type="submit">লগইন করুন</button>
        </form>
        <a class="home" href="{{ route('home') }}">← ওয়েবসাইটে ফিরে যান</a>
    </main>
</body>
</html>
