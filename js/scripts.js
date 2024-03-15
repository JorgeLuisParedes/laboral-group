function cargarUsuarios() {
	$.ajax({
		url: 'api.php',
		type: 'GET',
		success: function (data) {
			const datos = data;
			$('#tablaUsuarios').DataTable({
				data: datos,
				columns: [
					{ data: 'nId' },
					{
						data: 'sNombre',
						'render': function (data, type, row) {
							return `<a href='#' class='modalModificarUsuario' data-id='${row.nId}'>${row.sNombre}</a>`;
						}

					},
					{ data: 'sDni' },
					{
						data: 'dFechaNacimiento',
						'render': function (data, type, row) {
							if (type === 'display' || type === 'filter') {
								let fecha = data.split('-');
								return `${fecha[2]}-${fecha[1]}-${fecha[0]}`;
							}
							return data;
						}
					},
					{ data: 'sTelefono', defaultContent: '' },
					{ data: 'sEmail', defaultContent: '' }
				],
				columnDefs: [
					{
						'targets': [0],
						'visible': false,
						'searchable': false
					}
				],
				order: [[0, 'asc']]
			});
		}
	});
}

function modalModificarUsuario(nId) {
	$.ajax({
		url: `api.php?nId=${nId}`,
		type: 'GET',
		success: function (data) {
			const { nId, sNombre, sDni, sTelefono, sEmail, dFechaNacimiento } = data;

			$('#modalTitle').text('Modificar usuario');

			$('#sNombre').val(sNombre);
			$('#sDni').val(sDni);
			$('#sTelefono').val(sTelefono); nId
			$('#sEmail').val(sEmail);
			$('#dFechaNacimiento').val(dFechaNacimiento);
			$("#dFechaNacimiento").after(`<input type='hidden' name='nId' id='nId' value='${nId}'>`);

			$('#modalUsuario').modal('show');
		}
	});
}

function modalNuevoUsuario() {
	$('#nId').remove();
	$('#modalTitle').text('Nuevo usuario');
	$('#modalUsuario').modal('show');
}

function limpiarFormulario() {
	$('#sNombre, #sDni, #sTelefono, #sEmail, #dFechaNacimiento, #nId').val('');
}

function modificarUsuario(datosUsuario) {
	$.ajax({
		url: `api.php`,
		type: 'PUT',
		contentType: 'application/json',
		data: JSON.stringify(datosUsuario),
		success: function (response) {
			$('#modalUsuario').modal('hide');
			const { status } = response;

			let sTitulo = '';
			let sMensaje = '';

			if (status === 200) {
				sTitulo = 'Usuario actualizado';
				sMensaje = 'Los datos del usuario se han actualizado satisfactoriamente.';
			} else {
				sTitulo = 'Usuario no se pudo actualizar';
				sMensaje = 'Los datos del usuario no son correctos y no han podido actualizarse.';
			}

			modalRespuesta(sTitulo, sMensaje);

		}
	});
}

function nuevoUsuario(datosUsuario) {
	$.ajax({
		url: `api.php`,
		type: 'POST',
		contentType: 'application/json',
		data: JSON.stringify(datosUsuario),
		success: function (response) {
			$('#modalUsuario').modal('hide');
			const { status } = response;

			let sTitulo = '';
			let sMensaje = '';

			if (status === 200) {
				sTitulo = 'Usuario creado';
				sMensaje = 'Los datos del usuario se han guardado satisfactoriamente.';
			} else {
				sTitulo = 'Usuario no se pudo crear';
				sMensaje = 'Los datos del usuario no son correctos y no crearse.';
			}

			modalRespuesta(sTitulo, sMensaje);

		}
	});
}

function modalRespuesta(sTitulo, sMensaje) {
	$('#modalTitleRespuesta').text(sTitulo);
	$('#sMensaje').text(sMensaje);
	$('#modalRespuesta').modal('show');
}

$(() => {
	cargarUsuarios();

	$('#tablaUsuarios').on('click', '.modalModificarUsuario', function () {
		const nId = $(this).data('id');
		limpiarFormulario();
		modalModificarUsuario(nId);
	});

	$(document).on('click', '.modalNuevoUsuario', function () {
		limpiarFormulario();
		modalNuevoUsuario();
	});

	$('#formularioUsuario').submit(function (e) {
		e.preventDefault();

		const nId = Number($('#nId').val());
		const datosUsuario = {
			sNombre: $('#sNombre').val(),
			sDni: $('#sDni').val(),
			dFechaNacimiento: $('#dFechaNacimiento').val(),
			sTelefono: $('#sTelefono').val() ? $('#sTelefono').val() : '',
			sEmail: $('#sEmail').val() ? $('#sEmail').val() : ''
		}

		if (nId) {
			datosUsuario.nId = nId;
			modificarUsuario(datosUsuario);
			return;
		} {
			nuevoUsuario(datosUsuario);
			return;
		}
	});

	$(document).on('click', '#btnAceptar', function () {
		window.location.reload();
	});

});
