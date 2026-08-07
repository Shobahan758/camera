// স্ক্রলে নরম রিভিল অ্যানিমেশন
const observer = new IntersectionObserver((entries) => entries.forEach((entry) => { if (entry.isIntersecting) { entry.target.classList.add('show'); observer.unobserve(entry.target); } }), { threshold: .12 });
document.querySelectorAll('.reveal').forEach((item) => observer.observe(item));

// অর্ডারের পরিমাণ কমানো ও বাড়ানো
const quantityInput = document.getElementById('qty');
document.getElementById('qtyMinus')?.addEventListener('click', () => {
  quantityInput.value = Math.max(1, Number.parseInt(quantityInput.value, 10) - 1 || 1);
});
document.getElementById('qtyPlus')?.addEventListener('click', () => {
  quantityInput.value = Math.max(1, Number.parseInt(quantityInput.value, 10) + 1 || 1);
});
quantityInput?.addEventListener('change', () => {
  quantityInput.value = Math.max(1, Number.parseInt(quantityInput.value, 10) || 1);
});

// অর্ডার ফরম যাচাই ও Laravel backend-এ জমা
const form = document.getElementById('orderForm');
form?.addEventListener('submit', async (event) => {
  event.preventDefault();
  if (!form.checkValidity()) { event.stopPropagation(); form.classList.add('was-validated'); return; }

  const button = form.querySelector('[type="submit"]');
  const status = document.getElementById('formStatus');
  const originalLabel = button.innerHTML;
  button.disabled = true;
  button.textContent = 'অর্ডার পাঠানো হচ্ছে...';
  status.classList.add('d-none');

  try {
    const response = await fetch(form.action, {
      method: 'POST',
      body: new FormData(form),
      headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
    });
    const result = await response.json();
    if (!response.ok) throw new Error(result.message || 'তথ্যগুলো যাচাই করে আবার চেষ্টা করুন।');

    status.textContent = result.message;
    status.className = 'alert alert-success mt-3 mb-0';
    form.classList.remove('was-validated');
    form.reset();
  } catch (error) {
    status.textContent = error.message || 'অর্ডার পাঠানো যায়নি। আবার চেষ্টা করুন।';
    status.className = 'alert alert-danger mt-3 mb-0';
  } finally {
    button.disabled = false;
    button.innerHTML = originalLabel;
  }
});
