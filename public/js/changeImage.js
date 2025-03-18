const range = document.getElementById('range');
const image = document.getElementById('image');
const sizeValue = document.getElementById('size-value');

range.addEventListener('input', function() {
    const scale = this.value;
    image.style.width = `${scale * 10}%`;
    sizeValue.textContent = `Размер: ${scale}`;
});