(function(){
  // Hero carousel: build from existing .work-carousel images if present
  function initHeroCarousel(){
    const heroGrid = document.querySelector('.hero-grid');
    if(!heroGrid) return;
    // gather image sources from work carousel slides
    const imgs = Array.from(document.querySelectorAll('.work-carousel .slide img')).map(i=>i.getAttribute('src')).filter(Boolean);
    if(imgs.length === 0) return;
    // build carousel
    const wrapper = document.createElement('div'); wrapper.className = 'hero-carousel';
    const track = document.createElement('div'); track.className = 'hero-track';
    imgs.forEach(src=>{
      const s = document.createElement('div'); s.className = 'hero-slide';
      const img = document.createElement('img'); img.src = src; img.loading = 'lazy'; img.alt = '';
      s.appendChild(img); track.appendChild(s);
    });
    // clone first slide to allow smooth loop
    if(imgs.length>1){ const firstClone = track.children[0].cloneNode(true); track.appendChild(firstClone); }
    wrapper.appendChild(track); heroGrid.appendChild(wrapper);

    // carousel state
    let index = 0; let slideCount = track.children.length; let width = wrapper.clientWidth; let running = true; let animating=false;
    const speedMs = 2500; // time between slides
    const transitionMs = 420; // slide transition

    function resize(){ width = wrapper.clientWidth; track.style.transform = `translateX(${ -index * width }px)`; }
    window.addEventListener('resize', resize);

    track.style.transition = `transform ${transitionMs}ms cubic-bezier(.2,.9,.2,1)`;
    // autoplay loop
    let timer = setInterval(next, speedMs);
    function next(){ if(animating) return; animating=true; index++; track.style.transform = `translateX(${ -index * width }px)`; setTimeout(()=>{ if(index >= slideCount-1){ index = 0; track.style.transition = 'none'; track.style.transform = `translateX(0px)`; // force reflow
        void track.offsetWidth; track.style.transition = `transform ${transitionMs}ms cubic-bezier(.2,.9,.2,1)`; }
      animating=false; }, transitionMs+20); }

    // pause on hover/focus
    wrapper.addEventListener('mouseenter', ()=>{ running=false; clearInterval(timer); });
    wrapper.addEventListener('mouseleave', ()=>{ if(!running){ running=true; timer = setInterval(next, speedMs); }});

    // touch support (swipe)
    let startX=0,dx=0; wrapper.addEventListener('pointerdown', (e)=>{ startX = e.clientX; wrapper.setPointerCapture(e.pointerId); clearInterval(timer); });
    wrapper.addEventListener('pointermove', (e)=>{ if(startX===null) return; dx = e.clientX - startX; track.style.transition = 'none'; track.style.transform = `translateX(${ -index * width + dx }px)`; });
    wrapper.addEventListener('pointerup', (e)=>{ wrapper.releasePointerCapture(e.pointerId); track.style.transition = `transform ${transitionMs}ms cubic-bezier(.2,.9,.2,1)`; if(Math.abs(dx) > width * 0.15){ if(dx>0) index = Math.max(0, index-1); else index = Math.min(slideCount-1, index+1); } track.style.transform = `translateX(${ -index * width }px)`; dx=0; if(running){ clearInterval(timer); timer = setInterval(next, speedMs); } });

    // initial layout
    resize();
  }
  if(document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initHeroCarousel); else initHeroCarousel();
})();

// Simple reveal on scroll for elements with .reveal
(function(){
  function initReveal(){
    const els = document.querySelectorAll('.reveal');
    if(!els.length) return;
    const io = new IntersectionObserver((entries)=>{
      entries.forEach(e=>{
        if(e.isIntersecting){ e.target.classList.add('visible'); io.unobserve(e.target); }
      });
    },{rootMargin:'0px 0px -8% 0px',threshold:0.05});
    els.forEach(el=>io.observe(el));
  }
  if(document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initReveal); else initReveal();
})();
