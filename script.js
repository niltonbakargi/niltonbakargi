const overlay = document.getElementById('overlay');
const modal   = document.getElementById('modal');
const content = document.getElementById('modalContent');

function openModal(id) {
  const tpl = document.getElementById('tpl-' + id);
  if (!tpl) return;
  content.innerHTML = '';
  content.appendChild(tpl.content.cloneNode(true));
  overlay.classList.add('active');
  modal.classList.add('active');
}

function closeModal() {
  overlay.classList.remove('active');
  modal.classList.remove('active');
}

// Fecha com ESC
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeModal();
});

// Foto: mostra só se carregar com sucesso
const img = document.getElementById('profilePhoto');
const placeholder = document.getElementById('photoPlaceholder');

img.onload = () => {
  img.style.display = 'block';
  placeholder.style.display = 'none';
};
img.onerror = () => {
  img.style.display = 'none';
  placeholder.style.display = 'flex';
};
