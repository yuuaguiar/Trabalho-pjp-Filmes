function filtrarPorGenero() {
    const genero = document.getElementById('genero').value;
    const url = new URL(window.location.href);
    
    if (genero === 'Todos') {
        url.searchParams.delete('genero');
    } else {
        url.searchParams.set('genero', genero);
    }
    
    window.location.href = url.toString();
}

// Preenche o filtro com o valor atual da URL
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const generoFiltro = urlParams.get('genero');
    
    if (generoFiltro) {
        const select = document.getElementById('genero');
        select.value = generoFiltro;
    }
});