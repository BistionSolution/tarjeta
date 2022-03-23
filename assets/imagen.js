var imagen = document.getElementById("file_img");
var btnActualizar = document.getElementById('btn-actualizar');
imagen.onchange = function(e) 
{
	let reader = new FileReader();
	var peso = e.target.files[0].size; //peso en bytes
	reader.readAsDataURL(e.target.files[0]);
	reader.onload = function(){
		let preview = document.getElementById('preview'),
				image = document.createElement('img');
		image.src = reader.result;
		preview.innerHTML = '';
		preview.append(image);
	};
	btnActualizar.disabled = false;
	let texto = document.getElementById('text-img');
	texto.innerHTML = '';
	if (peso>2097152){ //Limitar el peso		
		texto.append('El peso de la imagen excede el permitido(2MB).');
		texto.style.color='#ff0000'
		btnActualizar.disabled = true;			
	}
}