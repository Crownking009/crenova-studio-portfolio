(function(){
  const formatter = v=>new Intl.NumberFormat('en-NG',{style:'currency',currency:'NGN',maximumFractionDigits:0}).format(v);
  function initCalculator(root){
    if(!root) return;
    const form = root.querySelector('form');
    const output = root.querySelector('.estimate-amount');
    const details = root.querySelector('.estimate-details');
    const presets = root.querySelectorAll('.presets [data-preset]');
    const saveBtn = root.querySelector('.save-estimate');
    const contactBtn = root.querySelector('.contact-estimate');
    const prices = {branding:120000,website:180000,app:350000,campaign:90000};
    // tuned base prices
    prices.branding = 200000;
    prices.website = 250000;
    prices.app = 700000;
    prices.campaign = 120000;
    const addon = {images:50000,cms:80000};
    function compute(){
      const data = new FormData(form);
      let total=0; let breakdown=[];
      for(const key of ['branding','website','app','campaign']){
        if(data.get(key)){
          total+=prices[key]; breakdown.push([key,prices[key]]);
        }
      }
      const complexity = data.get('complexity')||'standard';
      const mult = complexity==='small'?0.6:complexity==='large'?1.5:1;
      total = Math.round(total * mult);
      // user adjustment slider (percentage)
      const adjust = parseInt(data.get('adjust')||100,10)/100;
      total = Math.round(total * adjust);
      if(data.get('images')){ total += addon.images; breakdown.push(['images',addon.images]); }
      if(data.get('cms')){ total += addon.cms; breakdown.push(['cms',addon.cms]); }
      output.textContent = formatter(total || 0);
      details.innerHTML = breakdown.map(b=>`<li><strong>${b[0]}</strong>: ${formatter(b[1])}</li>`).join('') || '<li>No services selected</li>';
    }
    form.addEventListener('input', (e)=>{
      if(e.target.name==='adjust'){ root.querySelector('.adjust-value').textContent = e.target.value + '%'; }
      compute();
    });
    form.addEventListener('change', compute);
    compute();

    // presets
    presets?.forEach(btn=>btn.addEventListener('click', ()=>{
      const p = btn.dataset.preset;
      // reset
      form.reset();
      if(p==='basic'){ form.querySelector('[name=branding]').checked = true; form.querySelector('[name=complexity]').value='small'; }
      if(p==='standard'){ form.querySelector('[name=website]').checked = true; form.querySelector('[name=images]').checked = true; form.querySelector('[name=complexity]').value='standard'; }
      if(p==='premium'){ form.querySelector('[name=app]').checked = true; form.querySelector('[name=cms]').checked = true; form.querySelector('[name=images]').checked = true; form.querySelector('[name=complexity]').value='large'; }
      compute();
    }));

    // save estimate: attempt server-side POST, fallback to localStorage
    saveBtn?.addEventListener('click', async ()=>{
      const data = new FormData(form); let breakdownArr = [];
      for(const key of ['branding','website','app','campaign']) if(data.get(key)) breakdownArr.push(key);
      if(data.get('images')) breakdownArr.push('images'); if(data.get('cms')) breakdownArr.push('cms');
      const total = output.textContent;
      const detailsText = Array.from(details.querySelectorAll('li')).map(li=>li.textContent).join('\n');
      const name = prompt('Your name (optional):','');
      const email = prompt('Your email (optional):','');
      const payload = { name: name || 'Website estimate', email: email || '', message: `Estimate: ${total}\n\n${detailsText}` };
      // include CSRF token from meta
      const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
      try{
        const formBody = new FormData(); formBody.append('csrf', csrf); formBody.append('action','estimate_save'); formBody.append('name', payload.name); formBody.append('email', payload.email); formBody.append('message', payload.message);
        const resp = await fetch(window.location.pathname + '?action=estimate_save', { method: 'POST', body: formBody, credentials: 'same-origin' });
        if (resp.ok) {
          if(saveBtn){ const t=saveBtn.textContent; saveBtn.textContent='Saved ✓'; setTimeout(()=>saveBtn.textContent=t,1400); }
          return;
        }
      } catch (e) {}
      // fallback to localStorage if network/save fails
      const store = JSON.parse(localStorage.getItem('saved-estimates')||'[]');
      store.unshift({id:Date.now(), total, items:breakdownArr, complexity:data.get('complexity'), adjust:data.get('adjust')||100, name:payload.name, email:payload.email});
      localStorage.setItem('saved-estimates', JSON.stringify(store));
      if(saveBtn){ const t=saveBtn.textContent; saveBtn.textContent='Saved ✓'; setTimeout(()=>saveBtn.textContent=t,1400); }
    });

    // contact via mailto using site email found in footer
    contactBtn?.addEventListener('click', ()=>{
      let emailEl = document.querySelector('footer a[href^="mailto:"]');
      let to = emailEl ? emailEl.getAttribute('href').replace('mailto:','') : '';
      const body = [];
      body.push('Hello, I would like to enquire about a project.');
      body.push('\nEstimate: ' + (output.textContent||'₦0'));
      const detailsText = Array.from(details.querySelectorAll('li')).map(li=>li.textContent).join('\n');
      if(detailsText) body.push('\nBreakdown:\n' + detailsText);
      const subject = encodeURIComponent('Project estimate from website');
      const mailto = `mailto:${to}?subject=${subject}&body=${encodeURIComponent(body.join('\n'))}`;
      window.location.href = mailto;
    });
  }
  function initAll(){
    document.querySelectorAll('.cost-calculator').forEach(initCalculator);
  }
  if(document.readyState==='loading') document.addEventListener('DOMContentLoaded', initAll); else initAll();
})();
