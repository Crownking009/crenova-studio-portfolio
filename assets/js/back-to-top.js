(() => {
  const button = document.querySelector('.back-to-top');
  if (!button) return;
  const update = () => button.classList.toggle('is-visible', window.scrollY > 520);
  window.addEventListener('scroll', update, { passive: true });
  update();
  button.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
})();
