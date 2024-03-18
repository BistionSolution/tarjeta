// FUNCION PARA COPIAR EN TARJETA DE CUENTAS
// var copy_click = document.querySelector("i.fa-copy");


var este = jQuery('i.copy-token')
este.click(function(e){
    var id = e.target.id;
    console.log("xdxdxd : ",id);

    const input = document.querySelector('.textLink'+id);
    const message = document.querySelector('#tooltip');

    input.focus();
    document.execCommand('SelectAll');
    document.execCommand('copy');
    message.innerHTML = "Copiado al portapapeles";
    setTimeout(()=>message.innerHTML="",5000);

});

var este = jQuery('i.copy-url')
este.click(function(e){
    var id = e.target.id;
    console.log("xdxdxd : ",id);

    const input = document.querySelector('.textLink'+id);
    const message = document.querySelector('#tooltip');

    input.focus();
    document.execCommand('SelectAll');
    document.execCommand('copy');
    message.innerHTML = "Copiado al portapapeles";
    setTimeout(()=>message.innerHTML="",5000);

});