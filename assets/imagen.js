var imagen = document.getElementById("file_img");
var imagen_negocio = document.getElementById("file_img_business");
var btnActualizar = document.getElementById('btn-actualizar');
var peso_imagen=false;
var peso_imagen_business = false;

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
	let texto = document.getElementById('text-img');
	texto.innerHTML = '';
	if (peso>2097152){ //Limitar el peso		
		texto.append('El peso de la imagen excede el permitido(2MB).');
		texto.style.color='#ff0000'
		btnActualizar.disabled = true;
		peso_imagen = true;		
	}else{
		peso_imagen =false ;	
	}
	if (peso_imagen== false && peso_imagen_business==false){
		btnActualizar.disabled = false;	
	}
}

imagen_negocio.onchange = function(e) 
{
	let reader = new FileReader();
	var peso = e.target.files[0].size; //peso en bytes
	reader.readAsDataURL(e.target.files[0]);
	reader.onload = function(){
		let preview = document.getElementById('preview_business'),
				image = document.createElement('img');
		image.src = reader.result;
		preview.innerHTML = '';
		preview.append(image);
	};
	if (peso_imagen== false && peso_imagen_business==false){
		btnActualizar.disabled = false;
	}
	let texto = document.getElementById('text-img-business');
	texto.innerHTML = '';
	if (peso>2097152){ //Limitar el peso		
		texto.append('El peso de la imagen excede el permitido(2MB).');
		texto.style.color='#ff0000'
		btnActualizar.disabled = true;	
		peso_imagen_business = true;		
	} else{
		peso_imagen_business =false ;	
	}

	if (peso_imagen== false && peso_imagen_business==false){
		btnActualizar.disabled = false;
	}
}



