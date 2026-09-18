
document.addEventListener('DOMContentLoaded', function(){
  const toggle = document.querySelector('[data-mobile-toggle]');
  const sidebar = document.querySelector('.sidebar');
  if(toggle && sidebar) toggle.addEventListener('click', ()=>sidebar.classList.toggle('open'));

  const path = location.pathname.split('/').pop() || 'index.php';
  document.querySelectorAll('a[data-nav]').forEach(a=>{
    const href=(a.getAttribute('href')||'').split('/').pop();
    if(href===path) a.classList.add('active');
  });

  document.querySelectorAll('[data-confirm]').forEach(el=>{
    el.addEventListener('click', e=>{
      if(!confirm(el.getAttribute('data-confirm'))) e.preventDefault();
    });
  });

  const search = document.querySelector('[data-filter]');
  if(search){
    const target = document.querySelector(search.getAttribute('data-filter'));
    search.addEventListener('input', ()=>{
      const q=search.value.toLowerCase();
      target.querySelectorAll('[data-search-item]').forEach(item=>{
        item.style.display=item.textContent.toLowerCase().includes(q)?'':'none';
      });
    });
  }
});
