
const buto = document.querySelector('#buttongo');
const input2 = document.querySelector('.for-copy');
const message2 = document.querySelector('#tooltip');

buto.addEventListener('click', function(e){
    input2.setAttribute("value", input2)

    var inputFalso = document.createElement("input");
    inputFalso.setAttribute("value",input2.innerHTML)

    document.body.appendChild(inputFalso)
    inputFalso.select();

    document.execCommand('copy');
    document.body.removeChild(inputFalso);

    message2.innerHTML = "Copiado al portapapeles";
    setTimeout(()=>message2.innerHTML="",2000);
})