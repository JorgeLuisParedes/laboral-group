// Define la función para cargar los usuarios y mostrarlos en una tabla
function cargarUsuarios() {
	$.ajax({
		url: 'api.php', // Especifica la URL del API para obtener los datos
		type: 'GET', // Tipo de petición HTTP
		success: function (data) {
			const datos = data;
			// Inicializa DataTables en el elemento con ID tablaUsuarios
			$('#tablaUsuarios').DataTable({
				data: datos, // Asigna los datos recibidos a la tabla
				columns: [
					{ data: 'nId' }, // Muestra el ID del usuario (no visible pero usado en ordenación)
					{
						data: 'sNombre', // Muestra el nombre del usuario
						'render': function (data, type, row) {
							// Personaliza la celda para incluir un enlace para modificar el usuario
							return `<a href='#' class='modalModificarUsuario' data-id='${row.nId}'>${row.sNombre}</a>`;
						}
					},
					{ data: 'sDni' }, // Muestra el DNI del usuario
					{
						data: 'dFechaNacimiento', // Muestra la fecha de nacimiento
						'render': function (data, type, row) {
							// Formatea la fecha de nacimiento para su visualización
							if (type === 'display' || type === 'filter') {
								let fecha = data.split('-');
								return `${fecha[2]}-${fecha[1]}-${fecha[0]}`;
							}
							return data;
						}
					},
					{ data: 'sTelefono', defaultContent: '' }, // Muestra el teléfono, con contenido por defecto vacío si no existe
					{ data: 'sEmail', defaultContent: '' } // Muestra el email, con contenido por defecto vacío si no existe
				],
				columnDefs: [
					{
						'targets': [0], // Aplica la definición de columna al ID del usuario
						'visible': false, // Oculta la columna del ID
						'searchable': false // Hace que la columna del ID no sea buscable
					}
				],
				order: [[0, 'asc']] // Ordena la tabla por el ID del usuario de forma ascendente
			});
		}
	});
}

// Define la función para mostrar el modal de modificación de usuario con los datos precargados
function modalModificarUsuario(nId) {
	$.ajax({
		url: `api.php?nId=${nId}`, // Especifica la URL del API para obtener los datos del usuario por ID
		type: 'GET', // Tipo de petición HTTP
		success: function (data) {
			// Desestructura los datos recibidos para obtener los valores individuales
			const { nId, sNombre, sDni, sTelefono, sEmail, dFechaNacimiento } = data;

			// Configura el título del modal y los valores de los campos del formulario
			$('#modalTitle').text('Modificar usuario');
			$('#sNombre').val(sNombre);
			$('#sDni').val(sDni);
			$('#sTelefono').val(sTelefono);
			$('#sEmail').val(sEmail);
			$('#dFechaNacimiento').val(dFechaNacimiento);
			// Agrega un campo oculto con el ID del usuario para saber qué usuario se está modificando
			$("#dFechaNacimiento").after(`<input type='hidden' name='nId' id='nId' value='${nId}'>`);

			// Muestra el modal
			$('#modalUsuario').modal('show');
		}
	});
}

// Define la función para mostrar el modal para crear un nuevo usuario
function modalNuevoUsuario() {
	// Elimina el campo oculto con el ID del usuario si existe
	$('#nId').remove();
	// Configura el título del modal para la creación de un nuevo usuario
	$('#modalTitle').text('Nuevo usuario');
	// Muestra el modal
	$('#modalUsuario').modal('show');
}

// Define la función para limpiar los campos del formulario en el modal
function limpiarFormulario() {
	// Limpia los valores de todos los campos del formulario
	$('#sNombre, #sDni, #sTelefono, #sEmail, #dFechaNacimiento, #nId').val('');
}

// Define la función para enviar los datos modificados de un usuario al servidor
function modificarUsuario(datosUsuario) {
	$.ajax({
		url: `api.php`, // Especifica la URL del API para enviar los datos modificados
		type: 'PUT', // Tipo de petición HTTP
		contentType: 'application/json', // Especifica el tipo de contenido como JSON
		data: JSON.stringify(datosUsuario), // Convierte los datos del usuario a formato JSON
		success: function (response) {
			// Cierra el modal tras el éxito de la operación
			$('#modalUsuario').modal('hide');
			const { status } = response;

			let sTitulo = '';
			let sMensaje = '';

			// Personaliza el mensaje de respuesta según el estado de la operación
			if (status === 200) {
				sTitulo = 'Usuario actualizado';
				sMensaje = 'Los datos del usuario se han actualizado satisfactoriamente.';
			} else {
				sTitulo = 'Usuario no se pudo actualizar';
				sMensaje = 'Los datos del usuario no son correctos y no han podido actualizarse.';
			}

			// Muestra un modal con el resultado de la operación
			modalRespuesta(sTitulo, sMensaje);
		}
	});
}

// Define la función para enviar los datos de un nuevo usuario al servidor
function nuevoUsuario(datosUsuario) {
	$.ajax({
		url: `api.php`, // Especifica la URL del API para enviar los datos del nuevo usuario
		type: 'POST', // Tipo de petición HTTP
		contentType: 'application/json', // Especifica el tipo de contenido como JSON
		data: JSON.stringify(datosUsuario), // Convierte los datos del usuario a formato JSON
		success: function (response) {
			// Cierra el modal tras el éxito de la operación
			$('#modalUsuario').modal('hide');
			const { status } = response;

			let sTitulo = '';
			let sMensaje = '';

			// Personaliza el mensaje de respuesta según el estado de la operación
			if (status === 200) {
				sTitulo = 'Usuario creado';
				sMensaje = 'Los datos del usuario se han guardado satisfactoriamente.';
			} else {
				sTitulo = 'Usuario no se pudo crear';
				sMensaje = 'Los datos del usuario no son correctos y no crearse.';
			}

			// Muestra un modal con el resultado de la operación
			modalRespuesta(sTitulo, sMensaje);
		}
	});
}

// Define la función para mostrar un modal con el resultado de una operación (creación o modificación de usuario)
function modalRespuesta(sTitulo, sMensaje) {
	// Configura el título y el mensaje del modal de respuesta
	$('#modalTitleRespuesta').text(sTitulo);
	$('#sMensaje').text(sMensaje);
	// Muestra el modal de respuesta
	$('#modalRespuesta').modal('show');
}

// Ejecuta las siguientes instrucciones una vez que el DOM está completamente cargado
$(() => {
	// Carga inicialmente los usuarios y los muestra en la tabla
	cargarUsuarios();

	// Establece un manejador de evento para abrir el modal de modificación cuando se hace clic en un nombre de usuario
	$('#tablaUsuarios').on('click', '.modalModificarUsuario', function () {
		const nId = $(this).data('id');
		// Limpia el formulario y abre el modal para modificar el usuario con el ID especificado
		limpiarFormulario();
		modalModificarUsuario(nId);
	});

	// Establece un manejador de evento para abrir el modal para crear un nuevo usuario
	$(document).on('click', '.modalNuevoUsuario', function () {
		// Limpia el formulario y abre el modal para la creación de un nuevo usuario
		limpiarFormulario();
		modalNuevoUsuario();
	});

	// Establece un manejador de evento para el envío del formulario de usuario
	$('#formularioUsuario').submit(function (e) {
		e.preventDefault(); // Previene la recarga de la página

		// Obtiene el valor del campo oculto que indica si se está modificando un usuario existente
		const nId = Number($('#nId').val());
		// Recolecta los datos del formulario
		const datosUsuario = {
			sNombre: $('#sNombre').val(),
			sDni: $('#sDni').val(),
			dFechaNacimiento: $('#dFechaNacimiento').val(),
			sTelefono: $('#sTelefono').val() ? $('#sTelefono').val() : '',
			sEmail: $('#sEmail').val() ? $('#sEmail').val() : ''
		}

		// Decide si se debe modificar un usuario existente o crear uno nuevo basado en la presencia del ID del usuario
		if (nId) {
			datosUsuario.nId = nId;
			modificarUsuario(datosUsuario);
		} else {
			nuevoUsuario(datosUsuario);
		}
	});

	// Establece un manejador de evento para el botón de aceptar en el modal de respuesta
	// que recarga la página para reflejar los cambios
	$(document).on('click', '#btnAceptar', function () {
		window.location.reload();
	});

});
