// const copyToClipboard = (element) => {
//     const $tempInput = jQuery("<input>");

//     jQuery("body").append($tempInput);
//     $tempInput.val(jQuery(element).text()).select();
//     document.execCommand("copy");
//     $tempInput.remove();

//     tooltipFunction();
// };

// const tooltipFunction = () => {  
//     const tooltip = document.getElementById("tooltip");

//     tooltip.classList.add('active');
//     setTimeout(() => {
//        tooltip.classList.remove('active');
//     }, 1500);
// }

// jQuery(".copy-link .icon").click(function() {
//     copyToClipboard('.copy-link .text');

//     // With text marked.
//     textField.addClass('input_copy_selected');
// });

const button = document.querySelector('i.fa-copy');
const input = document.querySelector('.textLink');
const message = document.querySelector('#tooltip');

button.addEventListener('click', function(){
    input.focus();
    document.execCommand('SelectAll');
    document.execCommand('copy');

    message.innerHTML = "Copiado al portapapeles";
    setTimeout(()=>message.innerHTML="",4000);
    
})


