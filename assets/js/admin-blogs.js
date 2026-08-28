(function(){
  document.addEventListener('click', function (e) {
    const btn = e.target.closest('[data-open-modal]');
    if (!btn) return;
    // small delay to allow modal to be populated
    setTimeout(function(){
      const modal = document.querySelector('dialog.admin-modal');
      if (!modal) return;
      const form = modal.querySelector('form');
      if (!form) return;
      const res = form.querySelector('input[name="resource"]');
      if (!res || res.value !== 'blogs') return;
      if (form.querySelector('input[name="image"]')) return; // already present
      const label = document.createElement('label');
      label.innerHTML = 'Image<input type="file" name="image" accept="image/jpeg,image/png,image/webp">';
      const primary = form.querySelector('button.button.primary');
      if (primary) form.insertBefore(label, primary);
    }, 50);
  });
})();
