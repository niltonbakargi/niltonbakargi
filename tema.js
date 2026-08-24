// Chamado por cada página de tema após definir a variável global TEMA
function iniciarTema() {
  const t = window.TEMA;

  // Cores CSS
  document.documentElement.style.setProperty('--cor1', t.cor1);
  document.documentElement.style.setProperty('--cor2', t.cor2);

  // Título e ícone no hero
  document.getElementById('hero-icone').textContent = t.icone;
  document.getElementById('hero-titulo').textContent = t.nome;
  document.title = t.nome + ' — Nilton Bakargi';

  // Carousel do hero
  const hero = document.getElementById('hero');
  if (t.imagens && t.imagens.length > 0) {
    const slides = t.imagens.map(src => {
      const d = document.createElement('div');
      d.className = 'hero-slide';
      d.style.backgroundImage = `url(${src})`;
      hero.insertBefore(d, hero.firstChild);
      return d;
    });
    let atual = 0;
    slides[0].classList.add('ativo');
    if (slides.length > 1) {
      setInterval(() => {
        slides[atual].classList.remove('ativo');
        atual = (atual + 1) % slides.length;
        slides[atual].classList.add('ativo');
      }, 4500);
    }
  }

  // Galeria de fotos
  const galeria = document.getElementById('galeria-grid');
  if (galeria) {
    if (t.fotos && t.fotos.length > 0) {
      galeria.innerHTML = '';
      t.fotos.forEach(src => {
        const item = document.createElement('div');
        item.className = 'galeria-item';
        const img = document.createElement('img');
        img.src = src;
        img.alt = '';
        item.appendChild(img);
        galeria.appendChild(item);
      });
    } else {
      galeria.innerHTML = '<p class="galeria-vazia">As fotos aparecerão aqui assim que forem adicionadas.</p>';
    }
  }

  // Rótulo do botão de história
  const btnHistoria = document.getElementById('btn-historia');
  if (btnHistoria) {
    btnHistoria.textContent = t.historia || 'Nossa História';
  }

  // Tabs
  document.querySelectorAll('.nav-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('ativo'));
      document.querySelectorAll('.secao').forEach(s => s.classList.remove('ativo'));
      btn.classList.add('ativo');
      const alvo = document.getElementById('sec-' + btn.dataset.sec);
      if (alvo) alvo.classList.add('ativo');
    });
  });

  // Ativa a primeira tab
  const primeira = document.querySelector('.nav-btn');
  if (primeira) primeira.click();
}
