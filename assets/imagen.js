var imagen = document.getElementById("file_img");
var imagen_negocio = document.getElementById("file_img_business");
var delete_img = document.getElementById("delete_img");
var delete_img_b = document.getElementById("delete_img_business");
var btnActualizar = document.getElementById('btn-actualizar');
var peso_imagen=false;
var peso_imagen_business = false;

imagen.onchange = function(e) 
{
	let reader = new FileReader();
	var peso = e.target.files[0].size; //peso en bytes
	reader.readAsDataURL(e.target.files[0]);
	let texto = document.getElementById('text-img');
	texto.innerHTML = '';
	if (peso>5097152){ //Limitar el peso		
		texto.append('El peso de la imagen excede el permitido(2MB).');
		texto.style.color='#ff0000'
		// btnActualizar.disabled = true;
		peso_imagen = true;	
	}else{
		reader.onload = function(){
			let preview = document.getElementById('preview'),
					image = document.createElement('img');
			image.src = reader.result;
			preview.innerHTML = '';
			preview.append(image);
		};
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
	
	if (peso_imagen== false && peso_imagen_business==false){
		btnActualizar.disabled = false;
	}
	let texto = document.getElementById('text-img-business');
	texto.innerHTML = '';
	if (peso>5097152){ //Limitar el peso		
		texto.append('El peso de la imagen excede el permitido (2MB).');
		texto.style.color='#ff0000'
		btnActualizar.disabled = true;	
		peso_imagen_business = true;		
	} else{
		reader.onload = function(){
			let preview = document.getElementById('preview_business'),
					image = document.createElement('img');
			image.src = reader.result;
			preview.innerHTML = '';
			preview.append(image);
		};
		peso_imagen_business =false ;	
	}

	if (peso_imagen== false && peso_imagen_business==false){
		btnActualizar.disabled = false;
	}
}

delete_img.onclick = function(e) 
{
	
	imagen.value = '';
	let preview = document.getElementById('preview'),
			image = document.createElement('img');
			image.src = '';
			preview.innerHTML = '';
			preview.append(image);
	// jQuery('#cargaModal').modal('show');
	// jQuery.ajax({
	// 	type:"post",
	// 	url:ajax_object.url,
	// 	data:{
	// 		'action':'delete_img',
	// 		'id_vcard':jQuery('.identificador-vcard').val()
	// 	},
	// 	success: function(data){
	// 		var url = data.slice(0, -1);
	// 		let preview = document.getElementById('preview'),
	// 		image = document.createElement('img');
	// 		image.src = '';
	// 		preview.innerHTML = '';
	// 		preview.append(image);
	// 		jQuery('#delete_img').addClass('.trash-ocultar');
	// 	},
	// 	complete : function(xhr, status) {
			
	// 		jQuery('#cargaModal').modal('hide');
	// 	}
	// });  
}

delete_img_b.onclick = function(e) 
{
	imagen_negocio.value='';
	let preview = document.getElementById('preview_business'),
			image = document.createElement('img');
			image.src = '';
			preview.innerHTML = '';
			preview.append(image);
	
	// jQuery('#cargaModal').modal('show');
	// jQuery.ajax({
	// 	type:"post",
	// 	url:ajax_object.url,
	// 	data:{
	// 		'action':'delete_img_b',
	// 		'id_vcard':jQuery('.identificador-vcard').val()
	// 	},
	// 	success: function(data){
	// 		var url = data.slice(0, -1);
	// 		let preview = document.getElementById('preview_business'),
	// 		image = document.createElement('img');
	// 		image.src = '';
	// 		preview.innerHTML = '';
	// 		preview.append(image);
	// 		jQuery('#delete_img_business').addClass('.trash-ocultar');
	// 	},
	// 	complete : function(xhr, status) {
	// 		jQuery('#cargaModal').modal('hide');
	// 	}
	// }); 
		

}



