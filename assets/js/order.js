(() => {
  const button = document.querySelector('#whatsapp-checkout');
  if (!button) return;
  button.addEventListener('click', () => {
    const cart = JSON.parse(localStorage.getItem('crenova-cart') || '[]');
    const name = document.querySelector('#checkout-name').value.trim();
    const phone = document.querySelector('#checkout-phone').value.trim();
    const address = document.querySelector('#checkout-address').value.trim();
    if (!name || !phone || !address || !cart.length) return;
    const total = cart.reduce((sum, item) => sum + Number(item.price) * item.quantity, 0);
    const items = cart.map(item => `${item.name} x${item.quantity}`).join(', ');
    const body = new URLSearchParams({ action: 'order', csrf: document.querySelector('meta[name="csrf-token"]')?.content || '', name, phone, address, items, total: String(total) });
    fetch('/', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body }).catch(() => {});
  });
})();
