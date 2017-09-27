function isEmptyJSON(obj) {
	for(var i in obj) { return false; }
		return true;
}

$(function(){
	var listMenu = new Map(); //lista de items del menu
	//render menu
	$.ajax({
		url: "src/get-data.php?md=menu",
		method: "GET",
		async: true,
		cache: false,
		dataType: "json"

	}).done(function( dataUser ) {
		//console.log('user data', dataUser);
		$(".userImage").attr("src", dataUser.foto_persona);
		$(".userName").html(dataUser.apellido_persona + ' ' + dataUser.nombre_persona);
		//console.log("--", dataUser);
		$.each(dataUser.privilegio_perfil, function(subIndex, subMenu) {
			if(isEmptyJSON(subMenu)){
				//en caso de que un submenu este vacio, se remueve 
				$('#subMenu-'+subIndex).remove();
			}else{
				$('#subMenu-'+subIndex).show();
				$.each(subMenu, function(itemIndex, itemData){
					//se listan todos los items del subMenu para luego usarlos en el evento click
					listMenu.set("vista-"+itemIndex, itemData);

					//se añade el item al subMenu
					$('#subMenu-'+subIndex+' > ul').append(`
						<li id="vista-`+itemIndex+`" class="treeview botonMenu" >
						<a>
						<i class="fa fa-circle-o"></i>`
						+itemData.label+`
						</a> 
						</li>`);
				});


				$('.botonMenu').click(function(event) {
					var itemMenu = listMenu.get(event.currentTarget.id);
					$.get( "src/get-data.php?md=page&pg="+itemMenu.page)
					.done(function( data ) {
						$( ".content-wrapper" ).html( data );
						$('title').html('SGIM | '+itemMenu.label);
					});
				});
			}
		});
	}).fail(function(jqXHR, textStatus) {
		alert("Request failed: " + textStatus);
	});

	//pagina de inicio
	$.get( "src/get-data.php?md=page&pg=tablero")
	.done(function( data ) {
		$( ".content-wrapper" ).html( data );
	});

	$('.vista-tablero').click(function(){
		$.get( "src/get-data.php?md=page&pg=tablero")
		.done(function( data ) {
			$( ".content-wrapper" ).html( data );
			$('title').html('SGIM | Tablero');
		});
	});

	$('.vista-inicio').click(function(){
		$.get( "src/get-data.php?md=page&pg=inicio")
		.done(function( data ) {
			$( ".content-wrapper" ).html( data );
			$('title').html('SGIM | Inicio');
		});
	});
});