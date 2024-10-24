document.getElementById('cep').addEventListener('blur', function() {
    var cep = this.value.replace(/\D/g, '');
    if (cep.length === 8) {
        fetch(`https://viacep.com.br/ws/${cep}/json/`)
            .then(response => response.json())
            .then(data => {
                if (!data.erro) {
                    document.getElementById('cidade').value = data.localidade;
                    document.getElementById('estado').value = data.uf;
                }
            });
    }
});
