// document.addEventListener('contextmenu', (e) => e.preventDefault());
// document.addEventListener('keydown', (e) => {
//   if (e.key === "F12" ||
//       (e.ctrlKey && e.shiftKey && e.key === "I") ||
//       (e.ctrlKey && e.shiftKey && e.key === "C") ||
//       (e.ctrlKey && e.key === "U")) {
//       e.preventDefault();
//   }
// });

function verPassword() {
  var estadoVerPass = $('#inputPass').attr('type');
  if (estadoVerPass == 'password') {
    $('#inputPass').attr('type', 'text');
  } else {
    $('#inputPass').attr('type', 'password');
  }
}

function validaUserEnter() {
  //$('#respuestaLogin').html("<img src='images/load3.gif' alt='Cargando...' width='25'/>");
  var inputUser = $('#inputUser').val();
  var inputPass = $('#inputPass').val();
  //var inputTipoValidador  = $('#tipoValidador').is(':checked');
  if (inputUser == "") {
    alerta('error', 'RUT Invalido', 'Para ingresar al sistema, debes ingresar un RUT.');
    return false;
  }
  if (inputPass == "") {
    alerta('error', 'Contraseña Invalida', 'Para ingresar al sistema, debes ingresar una Contraseña.');
    return false;
  }
  parametros = {
    'type': 'validaUserEnter',
    'inputUser': inputUser,
    'inputPass': inputPass,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    // dataType: "html",
    beforeSend: function () {
    },
    success: function (response) {
      if (response == 0) {
        alerta('error', 'Ingreso al Sistema', 'Por favor, verificar si el rut esta en el formato correcto (sin guion, sin puntos y sin digito) o si la contraseña esta incorrecta.');
        $('#inputPass').val('');
        $('#inputUser').val('');
        return false;
      }
      if (response == 1) {
        window.location.href = "index.php";
      }
    },
    complete: function (response) {
    }
  });
}

function recuperarContrasena() {
  var inputUser = $('#inputUser').val();
  var token = grecaptcha.getResponse();
  if (token == "" || inputUser == "") {
    if (token == "" && inputUser == "") { alerta('error', 'Recuperar Contraseña', 'Verifique el reCAPTCHA y ingrese un RUT valido'); }
    if (token == "") { alerta('error', 'Recuperar Contraseña', 'Verifique el reCAPTCHA'); }
    if (inputUser == "") { alerta('error', 'Recuperar Contraseña', 'Ingrese un RUT valido'); }
    return false;
  }
  $('#button_recover').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...');
  $('#button_recover').attr('disabled', true);
  parametros = {
    'type': 'recuperarContrasena',
    'inputUser': inputUser,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
    },
    success: function (response) {
      if (response == 0) {
        alerta('error', 'Recuperar Contraseña', 'El rut no esta registrado o no existe. Intente nuevamente.');
        $('#inputUser').val('');
      } else {
        if (response == 2) {
          alerta('error', 'Recuperar Contraseña', 'El rut existe, pero el correo de recuperación no fue enviado. Intente nuevamente.');
          $('#inputUser').val('');
        } else {
          alerta('success', 'Recuperar Contraseña', 'Su contraseña fue enviada para el correo registrado.');
        }
      }
      $('#button_recover').html('Recuperar Contraseña');
      $('#button_recover').attr('disabled', false);
    },
    complete: function (response) {
    }
  });
}

function scrollToAnchorXid(id) {
  var aTag = $('#' + id);
  $('html,body').animate({ scrollTop: aTag.offset().top - 80 }, 200);
}

function scrollToAnchorXname(aid) {
  var aTag = $("a[name='" + aid + "']");
  $('html,body').animate({ scrollTop: aTag.offset().top }, 1000);
}

function notifica(tipo, titulo, texto) {
  toastr.options = {
    "closeButton": false,
    "debug": false,
    "newestOnTop": false,
    "progressBar": true,
    "positionClass": "toast-bottom-center",
    "preventDuplicates": false,
    "onclick": null,
    "showDuration": "300",
    "hideDuration": "1000",
    "timeOut": "5000",
    "extendedTimeOut": "1000",
    "showEasing": "swing",
    "hideEasing": "linear",
    "showMethod": "fadeIn",
    "hideMethod": "fadeOut"
  }
  titulo = (!titulo) ? 'Alerta Sistema' : titulo;
  var contenido = `<strong>${titulo}</strong>&nbsp;${texto}`;
  toastr[tipo](contenido);
}

function alerta(tipo, titulo, texto) {
  if (titulo == "" && texto == "") {
    Swal.fire("Error de Sistema", tipo, "warning");
  } else {
    Swal.fire(titulo, texto, tipo);
  }
  $('body').removeClass('swal2-height-auto');
}

function alertaBlokeaFondo(tipo, titulo, texto) {
  Swal.fire({
    showConfirmButton: false,
    allowOutsideClick: false,
    allowEscapeKey: false,
    title: titulo,
    text: texto,
    type: tipo
  });
}

function alertSesionExpirada(tipo, titulo, texto) {
  Swal.fire({
    allowOutsideClick: false,
    allowEscapeKey: false,
    title: titulo,
    text: texto,
    type: tipo,
  }).then(function () {
    location = "sign-in.php";
  })
}

function alertCambiaPass(tabla, texto, idOpcional) {
  if (tabla == 'credenciales') {
    //  alerta('question','Cambia Contraseña','Se procederá con el cambio....');
    Swal.fire({
      title: 'Cambio de Contraseña',
      text: texto,
      type: 'question',
      showCancelButton: true,
      confirmButtonText: 'Continuar',
      showLoaderOnConfirm: true,
      allowOutsideClick: true,
      allowEscapeKey: true,
      //input: 'password',
      //text: 'Debe ingresar una nueva contraseña',
      html:
        '<p>' + texto + '</p><input type="text" id="swal-input1" class="form-control swal2-input" maxlength="12" onkeypress="return permite(event,\'num\');" >',
      preConfirm: function () {
        return Promise.resolve($('#swal-input1').val());
      }
    }).then((result) => {
      if (result.value == undefined) { return false; }
      if (result.value == '') {
        alertCambiaPass("credenciales", "Debe ingresar una nueva contraseña.", idOpcional);
      }
      if (result.value.length < 5) {
        alertCambiaPass("credenciales", 'Debe ingresar una contraseña mas larga. <br>Debe tener mas que 5 numeros.', idOpcional);
      }
      if (result.value.length > 4) {
        alertCambiaPass_ejecuta(tabla, idOpcional, $('#swal-input1').val());
      }
    })
  }
}

function alertCambiaPass_ejecuta(tabla, id, nuevaPass) {
  parametros = {
    "type": 'alertCambiaPass_ejecuta',
    "tabla": tabla,
    "id": id,
    "nuevaPass": nuevaPass,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
    },
    success: function (response) {
      if (tabla == 'credenciales') {
        if (response == 1) {
          cambiaPassFromCambioContrasenaInicioSistema(id, nuevaPass);
          notifica('success', 'Se ha cambiado la contraseña.');
          window.location.href = "index.php";
        }
        if (response != 1) {
          alertCambiaPass("credenciales", "<h2>Ocurrió un Error.</h2>Debe tener mas que 5 numeros.", id);
        }
      }
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function pregunta(tipo, titulo, texto) {
  Swal.fire({
    title: titulo,
    text: texto,
    type: tipo,
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    cancelButtonText: 'No',
    confirmButtonText: 'Sí',
  },
    function (isConfirm) {
      if (isConfirm) {
        Swal.fire("Deleted!", "Your imaginary file has been deleted.", "success");
      } else {
        Swal.fire("Cancelled", "Your imaginary file is safe :)", "error");
      }
    })
}

function ordenaTabla(nombreTabla, orderColumna, tipoOrden) {
  $('#' + nombreTabla).DataTable({
    "dom": '<"row pb-3"<"col-xl"l><"col-xl text-center"B><"col-xl"f>><t><"row"<"col-xl"i><""p>>',
    "lengthMenu": [[100, 250, 500, 1000, -1], ["100", "250", "500", "1000", "Todos"]],
    buttons: [{ text: 'Exportar', extend: 'excelHtml5', title: 'DatosExportados', className: 'btn-primary' }, {
      text: 'Ver Columnas', extend: 'colvis', columnText: function (dt, idx, title) { return (idx + 1) + ': ' + title; }
    }],
    "language": { "url": "assets/vendor/datatables/dataTables.spanish.js" },
    "order": [[orderColumna, tipoOrden]]
  });
  $.fn.DataTable.ext.pager.numbers_length = 4;
}

function ordenaSelect(nombreSelect) {
  $("#" + nombreSelect).select2({ placeholder: "Seleccione...", language: "es", allowClear: true });
}

function ordenaSelectTodos() {
  $("select").select2({ placeholder: "Seleccione...", language: "es", allowClear: true });
}

function consultaEnSII(dondeRut, dondeDV, dondeNombre) {
  var rutOriginal = $('#' + dondeRut).val();
  var dvOriginal = $('#' + dondeDV).val();
  $.getJSON('https://siichile.herokuapp.com/consulta', { rut: rutOriginal + '-' + dvOriginal }, function (result) {
    if (result.error == 'Rut invalido' || result.razon_social == '**' || result.razon_social == '') {
      alerta('error', 'Error al ingresar el RUT', 'El rut que ingresó es Inválido. Por favor reintente nuevamente.');
      return false;
    } else {
      var nombreSII = $('#' + dondeNombre).val(result.razon_social);
      //var giroSII = $('#inputNuevo_giro').val(result.actividades[0].giro);
    }
  });
}

function formatearDocumento() {
  var tipoDoc = document.getElementById('tipoDocumentoID');
  var valDoc = tipoDoc.value;
  if(valDoc == 'Rut'){
    var inputRut = document.getElementById('documentoID');
    var rutSinGuion = inputRut.value.replace(/\./g, '').replace(/\-/g, ''); // Eliminar guiones y puntos
    var rutFormateado = rutSinGuion.replace(/^(\d{1,2})(\d{3})(\d{3})(\w{1})$/, '$1$2$3-$4'); // Formatear RUT con guión
    inputRut.value = rutFormateado;
  }
}

function sonido() {
  var popupSound = new Audio();
  if (navigator.userAgent.match("Firefox/")) {
    popupSound.src = "assets/audio/popup.ogg";
  } else {
    popupSound.src = "assets/audio/popup.mp3";
  }
  // sound setting saved on localStorage as 0 or 1, by default sound on (null value on localStorage)
  $globalVolume = localStorage.getItem('global-volume');
  if (($globalVolume == null || $globalVolume == '2')) {
    popupSound.play();
  }
}

function enDesarrollo() {
  alerta('info', 'En mantenimiento', 'Estamos trabajando para usted.<br>Pronto estará disponible esta funcionalidad. <br>Gracias.');
}

function cambiaPassFromCambioContrasenaInicioSistema(id, password) {
  parametros = {
    "type": 'cambiaPassFromCambioContrasenaInicioSistema',
    id: id,
    password: password,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    // dataType: "html",
    beforeSend: function () {
    },
    success: function (response) {
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function permite(elEvento, permitidos) {
  // Variables que definen los caracteres permitidos
  var k = "k";
  var numeros = "0123456789";
  var caracteres = " abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ¿?,áéíóúÁÉÍÓÚ_-";
  var letrasNum = " abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
  var numeros_caracteres = numeros + caracteres;
  var letras_num_special = numeros + letrasNum;
  var numeros_k = numeros + k;
  var teclas_especiales = [8, 37, 39, 46];
  // 8 = BackSpace, 46 = Supr, 37 = flecha izquierda, 39 = flecha derecha
  // Seleccionar los caracteres a partir del parámetro de la función
  switch (permitidos) {
    case 'num':
      permitidos = numeros;
      break;
    case 'car':
      permitidos = caracteres;
      brea
    case 'num_car':
      permitidos = numeros_caracteres;
      break;
    case 'num_car_vip':
      permitidos = letras_num_special;
      break;
    case 'dv':
      permitidos = numeros_k;
      break;
  }
  // Obtener la tecla pulsada
  var evento = elEvento || window.event;
  var codigoCaracter = evento.charCode || evento.keyCode;
  var caracter = String.fromCharCode(codigoCaracter);
  // Comprobar si la tecla pulsada es alguna de las teclas especiales
  // (teclas de borrado y flechas horizontales)
  var tecla_especial = false;
  for (var i in teclas_especiales) {
    if (codigoCaracter == teclas_especiales[i]) {
      tecla_especial = true;
      break;
    }
  }
  // Comprobar si la tecla pulsada se encuentra en los caracteres permitidos
  // o si es una tecla especial
  return permitidos.indexOf(caracter) != -1 || tecla_especial;
}

function permiteDV(elEvento, permitidos) {
  // Variables que definen los caracteres permitidos
  var numeros = "0123456789";
  var caracteres = "kK";
  var numeros_caracteres = numeros + caracteres;
  var teclas_especiales = [8, 37, 39, 46];
  // 8 = BackSpace, 46 = Supr, 37 = flecha izquierda, 39 = flecha derecha
  // Seleccionar los caracteres a partir del parámetro de la función
  switch (permitidos) {
    case 'num':
      permitidos = numeros;
      break;
    case 'car':
      permitidos = caracteres;
      brea
    case 'num_car':
      permitidos = numeros_caracteres;
      break;
  }
  // Obtener la tecla pulsada
  var evento = elEvento || window.event;
  var codigoCaracter = evento.charCode || evento.keyCode;
  var caracter = String.fromCharCode(codigoCaracter);
  // Comprobar si la tecla pulsada es alguna de las teclas especiales
  // (teclas de borrado y flechas horizontales)
  var tecla_especial = false;
  for (var i in teclas_especiales) {
    if (codigoCaracter == teclas_especiales[i]) {
      tecla_especial = true;
      break;
    }
  }
  // Comprobar si la tecla pulsada se encuentra en los caracteres permitidos
  // o si es una tecla especial
  return permitidos.indexOf(caracter) != -1 || tecla_especial;
}

function validaRut() {
  var ruti = $('#rut').val();
  //var dvi = $F('dvID');
  //var rut = ruti+"-"+dvi;
  if (ruti.length < 8) {
    alerta('error', 'Ingreso', 'El RUT no esta completo!!!');
    //alerta('El RUT no esta completo!!');
    return false;
  }
}

function validarut() {
  var ruti = $('#rutID').val();
  var dvi = $('#dvID').val();
  var rut = ruti + "-" + dvi;
  if (rut.length < 8) {
    alerta('error', 'Ingreso', 'El RUT no esta completo!!!');
    //alerta('El RUT no esta completo!!!');
    return (false);
  }
  i1 = rut.indexOf("-");
  dv = rut.substr(i1 + 1);
  dv = dv.toUpperCase();
  nu = rut.substr(0, i1);
  cnt = 0;
  suma = 0;
  for (i = nu.length - 1; i >= 0; i--) {
    dig = nu.substr(i, 1);
    fc = cnt + 2;
    suma += parseInt(dig) * fc;
    cnt = (cnt + 1) % 6;
  }
  dvok = 11 - (suma % 11);
  if (dvok == 11) dvokstr = "0";
  if (dvok == 10) dvokstr = "K";
  if ((dvok != 11) && (dvok != 10)) dvokstr = "" + dvok;
  if (dvokstr == dv)
    //alerta('RUT Valido!!');
    modalCompruebaRutContratos('Validación de Rut');
  else
    alerta('error', 'Ingreso', 'El RUT es invalido');
}

function validarut_IdRut(idRut, idDv) {
  var rutA = $('#' + idRut).val();
  if (!rutA || !rutA.length || typeof rutA !== 'string') {
    alerta('error', 'Ingreso', 'El RUT no esta completo!!!');
    //alerta('El RUT no esta completo!!!');
    return (false);
  }
  // serie numerica
  var secuencia = [2, 3, 4, 5, 6, 7, 2, 3];
  var sum = 0;
  //
  for (var i = rutA.length - 1; i >= 0; i--) {
    var d = rutA.charAt(i)
    sum += new Number(d) * secuencia[rutA.length - (i + 1)];
  };
  // sum mod 11
  var rest = 11 - (sum % 11);
  // si es 11, retorna 0, sino si es 10 retorna K,
  // en caso contrario retorna el numero
  if (rest === 11) {
    rest = '0';
  }
  if (rest === 10) {
    rest = 'k';
  }
  $('#' + idDv).val(rest);
}

function version_System() {
  $('#respuestaVersion_System').html('<div class="spinner-border text-primary" role="status"></div>');
  parametros = {
    'type': 'version_System',
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
    },
    success: function (response) {
      $('#respuestaVersion_System').html(response);
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function eliminarLinea(tabla, idLinea) {
  if (tabla == 'equipos') {
    textSwal = 'Este proceso va a borrar todos los jugadores de este equipo y esta acción es irreversible. ¿Está seguro que desea continuar?';
    typeSwal = 'error';
  } else {
    typeSwal = 'question';
    textSwal = 'Está seguro que quiere eliminar? Este proceso es Irreversible.';
  }
  Swal.fire({
    title: 'Eliminar',
    text: textSwal,
    type: typeSwal,
    showCancelButton: true,
    confirmButtonColor: '#00A28A',
    cancelButtonColor: '#e83a54',
    confirmButtonText: 'Sí',
    cancelButtonText: 'No'
  }).then((result) => {
    if (result.value == true) {
      parametros = {
        'type': 'eliminarLinea',
        'tabla': tabla,
        'idLinea': idLinea,
      };
      $.ajax({
        data: parametros,
        url: "option.php",
        type: "get",
        beforeSend: function () {
        },
        success: function (response) {
          if (response == 1) {
            notifica('success', 'El registro fue eliminado con éxito.', '');
            if (tabla == 'credenciales') { $('#listarUsuarios').click(); }
            if (tabla == 'jugadores') {
              var fotourl = $('#fotoJugador_' + idLinea).attr('alt');
              if (fotourl != 'sinimagen300x300.png') {
                eliminaFotoGen(idLinea, 'foto', fotourl);
                $('#listarJugadores').click();
              } else {
                $('#listarJugadores').click();
              }
            }
            if (tabla == 'competiciones') { $('#listarCompeticiones').click(); }
            if (tabla == 'equipos') { $('#listarEquipos').click(); }
            if (tabla == 'jugadores_seguros') { $('#listarJugadoresSeguro').click(); }
            if (tabla == 'seguro_opciones') { $('#opcSeguros').click(); }
          } else {
            notifica('error', 'Eliminar', ' Ocurrió un problema. Intente nuevamente.');
          }
        },
        complete: function (response) {
        },
        error: function (e) {
          notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
        }
      });
    }
  })
}

function eliminarMasivo(tabla, idLinea) {
  var arrayIds = new Array();

  $('input[name="jugadoresCheck"]:checked').each(function () {
    arrayIds.push($(this).attr("id"));
  });

  if (tabla == 'jugadores') {
    typeSwal = 'question';
    textSwal = 'Está seguro que quiere eliminar? Este proceso es Irreversible.';
  }
  Swal.fire({
    title: 'Eliminar',
    text: textSwal,
    type: typeSwal,
    showCancelButton: true,
    confirmButtonColor: '#00A28A',
    cancelButtonColor: '#e83a54',
    confirmButtonText: 'Sí',
    cancelButtonText: 'No'
  }).then((result) => {
    if (result.value == true) {
      parametros = {
        'type': 'eliminarMasivo',
        'tabla': tabla,
        'idLinea': idLinea,
        'arrayIds': JSON.stringify(arrayIds),
      };
      $.ajax({
        data: parametros,
        url: "option.php",
        type: "get",
        beforeSend: function () {
        },
        success: function (response) {
          if (response == 1) {
            notifica('success', 'Los registros fueron eliminados con éxito.');
            eImgNoUsadas();
            $('#listarJugadores').click();
          } else {
            notifica('error', 'No todos los registros fueron eliminados.');
          }
        },
        complete: function (response) {
        },
        error: function (e) {
          notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
        }
      });
    }
  })
}

function eliminarLineaTodas(tabla, idLinea, nombreCampo) {
  Swal.fire({
    title: 'Eliminar',
    text: 'Está seguro que quiere eliminar? Este proceso es Irreversible.',
    type: 'question',
    showCancelButton: true,
    confirmButtonColor: '#00A28A',
    cancelButtonColor: '#e83a54',
    confirmButtonText: 'Sí',
    cancelButtonText: 'No'
  }).then((result) => {
    if (result.value == true) {
      parametros = {
        'type': 'eliminarLineaTodas',
        'tabla': tabla,
        'idLinea': idLinea,
        'nombreCampo': nombreCampo,
      };
      $.ajax({
        data: parametros,
        url: "option.php",
        type: "get",
        beforeSend: function () {
        },
        success: function (response) {
          if (response == 0) {
            notifica('warning', 'La eliminación ha fallado.');
          }
          if (response == 1) {
            notifica('success', 'El registro fue eliminado con éxito.', '');
            if (tabla == 'credenciales') { credencialesInicio(); scrollToAnchorXid('card00'); }
            if (tabla == 'item') { actualizarTablaTR(idLinea); }
          } else {
            notifica('error', 'Eliminar', ' Ocurrió un problema. Intente nuevamente.');
          }
        },
        complete: function (response) {
        },
        error: function (e) {
          notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
        }
      });
    }
  })
}

function entregaDV(dondeRut, dondeDV, dondeNombre) {
  var T = $('#' + dondeRut).val();
  var M = 0, S = 1;
  for (; T; T = Math.floor(T / 10))
    S = (S + T % 10 * (9 - M++ % 6)) % 11;
  $('#' + dondeDV).val(S ? S - 1 : 'k');
  setTimeout(function () {
    consultaEnSII(dondeRut, dondeDV, dondeNombre);
  }, 1000);
}

function asideEsconde() {
  var asideEsconde = $('#asideEsconde').attr('class');
  if (asideEsconde == "app-aside app-aside-expand-md app-aside-light") {
    $('#asideEsconde').attr('class', 'app-aside app-aside-light');
  } else {
    $('#asideEsconde').attr('class', 'app-aside app-aside-expand-md app-aside-light');
    $('.aside-backdrop').removeClass('show');
    $('.hamburger').removeClass('active');
  }
}

function asideEscondeMobile() {
  $('#asideEsconde').attr('class', 'app-aside app-aside-expand-md app-aside-light');
  $('.aside-backdrop').removeClass('show');
  $('.hamburger').removeClass('active');
}

function cambioCheckbox(tabla, desdeDonde, campoAcambiar, idLinea) {
  var valorCheckbox = $(desdeDonde).prop('checked');
  parametros = {
    'type': 'cambioCheckbox_ejecuta',
    'tabla': tabla,
    'campoAcambiar': campoAcambiar,
    'idLinea': idLinea,
    'valorCheckbox': valorCheckbox,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
    },
    success: function (response) {
      if (response == 1) {
        notifica('success', '', 'Se ha cambiado la Opción.');
      } else {
        notifica('error', '', 'Ocurrió un problema. Intente nuevamente.');
      }
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function PrintContent(divEnviado) {
  var DocumentContainer = document.getElementById(divEnviado);
  var WindowObject = window.open("", "PrintWindow", "width=750,height=650,top=50,left=50,toolbars=no,scrollbars=yes,status=no,resizable=yes");
  WindowObject.document.writeln(DocumentContainer.innerHTML);
  WindowObject.document.close();
  WindowObject.focus();
  WindowObject.print();
  WindowObject.close();
}

function btnAsideOpenClose() {
  var estadoPage = $('#page').attr('class');
  if (estadoPage == 'page') {
    $('#page').attr('class', 'page has-sidebar-expand');
  } else {
    $('#page').attr('class', 'page');
  }
}

function PrintContent(divEnviado) {
  var DocumentContainer = document.getElementById(divEnviado);
  var WindowObject = window.open("", "PrintWindow", "width=750,height=650,top=50,left=50,toolbars=no,scrollbars=yes,status=no,resizable=yes");
  WindowObject.document.writeln(DocumentContainer.innerHTML);
  WindowObject.document.close();
  WindowObject.focus();
  WindowObject.print();
  WindowObject.close();
}
/* NUEVA LIBRERÍA DE PDF */
function imprimePDF(nombreIdEnviado, datoEnviado) {
  if (nombreIdEnviado == 'modalBody') { $('#btnPDFenvia').attr('hidden', true) }
  var fechaArchivo = Date.now();
  var element = document.getElementById(nombreIdEnviado);
  var opt = {
    margin: [0.5, 0.5, 0, 0.5], //[top, left, bottom, right]
    filename: "Exportado_" + datoEnviado + '_' + fechaArchivo + '.pdf',
    image: { type: 'jpeg', quality: 1.0 },
    pagebreak: { mode: 'avoid-all', before: '#page2el' },
    html2canvas: {
      dpi: 192,
      scale: 2,
      scrollY: 0,
      letterRendering: true,
      useCORS: true
    },
    jsPDF: { unit: 'cm', format: 'a4', orientation: 'landscape' }
  };
  html2pdf().from(element).set(opt).toPdf().get('pdf').then(function (pdf) {
    var totalPages = pdf.internal.getNumberOfPages();
    for (var i = 1; i <= totalPages; i++) {
      pdf.setPage(i);
      pdf.setFontSize(8);
      pdf.setTextColor(0);
      //pdf.text(pdf.internal.pageSize.getWidth() - 20, pdf.internal.pageSize.getHeight() - 0.5, "Código " +leftTxt);
      //pdf.text(pdf.internal.pageSize.getWidth() - 3, pdf.internal.pageSize.getHeight() - 0.5, "Página " +i+" de "+totalPages);
    }
  }).save();
}

function checkImageSize(inputID) {
  var maxSizeMb = 2;
  var file = $('#' + inputID)[0].files[0];
  if (file !== undefined) {
    //Get the size of the input file.
    var totalSize = file.size;
    //Convert bytes into MB.
    var totalSizeMb = totalSize / Math.pow(1024, 2);
    //Check to see if it is too large.
    if (totalSizeMb > maxSizeMb) {
      var errorMsg = 'File too large. Maximum file size is ' + maxSizeMb + 'MB. Selected file is ' + totalSizeMb.toFixed(2) + 'MB';
      notifica('warning', errorMsg);
      // empty filesize
      $('#fotoID').val('');
      return false;
    }
  }
}
/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
////////////////////////////  FIN AYUDAS ////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
///////////////////////////////  ADM   //////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
function credencialesInicio(idUsuario, idModulo) {
  //new muestraAccesosUsuario(idUsuario,idModulo);
  //$('#modalBtnGuardar').removeClass('hide');
  $('#opcion').html('<div class="spinner-border text-primary" role="status"></div>');
  $('#respuestaSVO_vxrutEscribe').empty();
  //new muestraAccesosUsuario(idUsuario,idModulo);
  parametros = {
    "type": 'credencialesInicio',
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
      $('#opcion-title').html('Usuarios del Sistema');
    },
    success: function (response) {
      $('#opcion').html(response);
    },
    complete: function (response) {
      ordenaTabla('tablacredencialesInicio', '4', 'asc');
      // setTimeout(function(){
      //   $('[data-toggle="tooltip"]').tooltip();
      // },1000);
      // setTimeout(function(){
      //   ordenaTabla('tablacredencialesInicio','3','asc');
      // },1000);
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function credenciales_nuevo(tabla, modalTitle) {
  parametros = {
    "type": 'credenciales_nuevo',
    "tabla": tabla,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    // dataType: "html",
    beforeSend: function () {
    },
    success: function (response) {
      $('#opcion-title').html(modalTitle);
      $('#opcion').html(response);
      ordenaSelectTodos();
    },
    complete: function (response) {
      ordenaSelectTodos();
      $('#desbloqueaNombre').change(function () {
        var esExtrajero = $('#desbloqueaNombre').prop('checked');
        if (esExtrajero == true) {
          //notifica('info','Es extranjero');
          $('#inputNuevo_rut').attr('onblur', "");
          $('#inputNuevo_dv').attr('disabled', false);
          $('#inputNuevo_nombre').attr('disabled', false);
        } else {
          //notifica('info','NO Es extranjero');
          $('#inputNuevo_rut').attr('onblur', "entregaDV('inputNuevo_rut','inputNuevo_dv')");
          $('#inputNuevo_dv').attr('disabled', true);
          $('#inputNuevo_nombre').attr('disabled', true);
        }
      }
      );
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function credenciales_nuevoEjecuta(tabla) {
  if (tabla == 'credenciales') {
    var inputNuevo_rut = $('#inputNuevo_rut').val();
    var inputNuevo_dv = $('#inputNuevo_dv').val();
    var inputNuevo_nombre = $('#inputNuevo_nombre').val();
    var inputNuevo_email = $('#inputNuevo_email').val();
    var inputNuevo_pass = $('#inputNuevo_pass').val();
    var inputNuevo_nivel = $('#inputNuevo_nivel1').val();
    var inputNuevo_hash = $('#inputNuevo_hash').val();
    var inputNuevo_equipo2 = $('#inputNuevo_equipo').select2('val');
    var inputNuevo_equipo = JSON.stringify(inputNuevo_equipo2);
    if (inputNuevo_nivel == '99') {
      notifica('error', '', 'Faltan datos por completar en el formulario');
      return false;
    }
    if (inputNuevo_rut == '' || inputNuevo_dv == '' || inputNuevo_email == '' || inputNuevo_nombre == '' || inputNuevo_pass == '' || inputNuevo_equipo == '') {
      notifica('error', '', 'Faltan datos por completar en el formulario');
      return false;
    }
    parametros = {
      "type": 'credenciales_nuevoEjecuta',
      "inputNuevo_rut": inputNuevo_rut,
      "inputNuevo_dv": inputNuevo_dv,
      "inputNuevo_nombre": inputNuevo_nombre,
      "inputNuevo_email": inputNuevo_email,
      "inputNuevo_pass": inputNuevo_pass,
      "inputNuevo_nivel": inputNuevo_nivel,
      "inputNuevo_hash": inputNuevo_hash,
      "inputNuevo_equipo": inputNuevo_equipo,
      "tabla": tabla,
    };
  }
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
    },
    success: function (response) {
      if (response == 1) {
        notifica('success', 'Los datos fueron ingresados correctamente.');
        if (tabla == 'credenciales') { credencialesInicio(); scrollToAnchorXid('card00'); }
      } else if (response == 2) {
        alerta('error', 'Agregar Credenciales', 'El Rut ingresado ya existe en nuestro sistema.');
      } else {
        alerta('error', 'Agregar Credenciales', 'Algo Falló. Intente nuevamente.');
      }
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function credenciales_editar(tabla, modalTitle, idUsuario) {
  parametros = {
    "type": 'credenciales_editar',
    "tabla": tabla,
    "idUsuario": idUsuario,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    // dataType: "html",
    beforeSend: function () {
    },
    success: function (response) {
      $('#opcion-title').html(modalTitle);
      $('#opcion').html(response);
    },
    complete: function (response) {
      ordenaSelectTodos();
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function credenciales_editarEjecuta(tabla, idUsuario) {
  if (tabla == 'credenciales') {
    var inputNuevo_rut = $('#inputNuevo_rut').val();
    var inputNuevo_dv = $('#inputNuevo_dv').val();
    var inputNuevo_nombre = $('#inputNuevo_nombre').val();
    var inputNuevo_email = $('#inputNuevo_email').val();
    var inputNuevo_pass = $('#inputNuevo_pass').val();
    var inputNuevo_nivel = $('#inputNuevo_nivel').val();
    var inputNuevo_hash = $('#inputNuevo_hash').val();
    var inputNuevo_equipo2 = $('#inputNuevo_equipo').select2('val');
    var inputNuevo_equipo = JSON.stringify(inputNuevo_equipo2);

    if (inputNuevo_nivel == '99') {
      notifica('error', '', 'Faltan datos por completar en el formulario');
      return false;
    }
    if (inputNuevo_rut == '' || inputNuevo_dv == '' || inputNuevo_email == '' || inputNuevo_nombre == '' || inputNuevo_pass == '') {
      notifica('error', '', 'Faltan datos por completar en el formulario');
      return false;
    }
    parametros = {
      "type": 'credenciales_editarEjecuta',
      "inputNuevo_rut": inputNuevo_rut,
      "inputNuevo_dv": inputNuevo_dv,
      "inputNuevo_nombre": inputNuevo_nombre,
      "inputNuevo_email": inputNuevo_email,
      "inputNuevo_pass": inputNuevo_pass,
      "inputNuevo_nivel": inputNuevo_nivel,
      "inputNuevo_hash": inputNuevo_hash,
      "inputNuevo_equipo": inputNuevo_equipo,
      "tabla": tabla,
      "idUsuario": idUsuario,
    };
  }
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    // dataType: "html",
    beforeSend: function () {
    },
    success: function (response) {
      if (response == 1) {
        notifica('success', 'Los datos fueron editados correctamente.');
        if (tabla == 'credenciales') { credencialesInicio(); scrollToAnchorXid('card00'); }
      } else {
        alerta('error', 'Editar Credenciales', 'Algo Falló. Intente nuevamente.');
      }
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function enviarCorreoVarios(tabla, idEnviado, emailAenviar, desdeDonde) {
  Swal.fire({
    title: 'Usuario y Contraseña',
    text: 'Desea enviar los datos al Usuario? ',
    type: 'question',
    showCancelButton: true,
    confirmButtonColor: '#00A28A',
    cancelButtonColor: '#e83a54',
    confirmButtonText: 'Sí',
    cancelButtonText: 'No'
  }).then((result) => {
    if (result.value == true) {
      parametros = {
        "type": 'enviarCorreoVarios',
        "tabla": tabla,
        "idEnviado": idEnviado,
        "emailAenviar": emailAenviar,
        "desdeDonde": desdeDonde,
      };
      $.ajax({
        data: parametros,
        url: "option.php",
        type: "get",
        // dataType: "html",
        beforeSend: function () {
        },
        success: function (response) {
          if (response == 1) {
            if (desdeDonde == 'deCredenciales') {
              $('#sidebar-body').append('<div>Usuario y Contraseña -> Enviado.</div>');
              notifica('success', 'Los datos fueron enviados correctamente.');
            }
          } else {
            notifica('error', 'Algo Falló. Intente nuevamente.');
          }
        },
        complete: function (response) {
        },
        error: function (e) {
          notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
        }
      });
    } else {
    }
  });
}

function enviarCorreosTic(tabla, idEnviado, emailAenviar, desdeDonde) {
  Swal.fire({
    title: 'Dar aviso devolución',
    text: 'Desea dar aviso de devolución para este equipo? ',
    type: 'question',
    showCancelButton: true,
    confirmButtonColor: '#00A28A',
    cancelButtonColor: '#e83a54',
    confirmButtonText: 'Sí',
    cancelButtonText: 'No'
  }).then((result) => {
    if (result.value == true) {
      parametros = {
        "type": 'enviarCorreosTic',
        "tabla": tabla,
        "idEnviado": idEnviado,
        "emailAenviar": emailAenviar,
        "desdeDonde": desdeDonde,
      };
      $.ajax({
        data: parametros,
        url: "option.php",
        type: "get",
        // dataType: "html",
        beforeSend: function () {
        },
        success: function (response) {
          if (response == 1) {
            $('#sidebar-body').append('<div>Solicitar Devolución Equipos TIC -> Enviado.</div>');
            notifica('success', 'Los datos fueron enviados correctamente.');
          } else {
            notifica('error', 'Algo Falló. Intente nuevamente.');
          }
        },
        complete: function (response) {
        },
        error: function (e) {
          notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
        }
      });
    } else {
    }
  });
}

function cambiaATesoreria(tabla, idEnviado, estadoFinal, emailAenviar) {
  var emailUsuario = $('#emailUsuario').val();
  Swal.fire({
    title: 'Cambio de Estado',
    text: 'Desea cambiar el Estado de esta Rendición a "En Tesorería"? ',
    type: 'question',
    showCancelButton: true,
    confirmButtonColor: '#00A28A',
    cancelButtonColor: '#e83a54',
    confirmButtonText: 'Sí',
    cancelButtonText: 'No'
  }).then((result) => {
    if (result.value == true) {
      parametros = {
        "type": 'cambiaATesoreria',
        "tabla": tabla,
        "idEnviado": idEnviado,
        "estadoFinal": estadoFinal,
        "emailAenviar": emailAenviar,
        "emailUsuario": emailUsuario,
      };
      $.ajax({
        data: parametros,
        url: "option.php",
        type: "get",
        // dataType: "html",
        beforeSend: function () {
        },
        success: function (response) {
          if (response == 1) {
            $('#sidebar-body').append('<div>Cambio Estado -> A "En Tesorería".</div>');
            validarRGAdmin('Rendiciones en Contabilidad', emailUsuario, 'En Contabilidad', 'contabilidad');
            scrollToAnchorXid('card00');
            notifica('success', 'Estado cambiado a "En Tesorería" correctamente.');
          } else {
            notifica('error', 'Algo Falló. Intente nuevamente.');
          }
        },
        complete: function (response) {
        },
        error: function (e) {
          notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
        }
      });
    } else {
    }
  });
}

function cambiaAPagoRealizado(tabla, idEnviado, estadoFinal, emailAenviar) {
  var emailUsuario = $('#emailUsuario').val();
  Swal.fire({
    title: 'Cambio de Estado',
    text: 'Desea cambiar el Estado de esta Rendición a "Pago Realizado" y avisar al Usuario? ',
    type: 'question',
    showCancelButton: true,
    confirmButtonColor: '#00A28A',
    cancelButtonColor: '#e83a54',
    confirmButtonText: 'Sí',
    cancelButtonText: 'No'
  }).then((result) => {
    if (result.value == true) {
      parametros = {
        "type": 'cambiaAPagoRealizado',
        "tabla": tabla,
        "idEnviado": idEnviado,
        "estadoFinal": estadoFinal,
        "emailAenviar": emailAenviar,
        "emailUsuario": emailUsuario,
      };
      $.ajax({
        data: parametros,
        url: "option.php",
        type: "get",
        // dataType: "html",
        beforeSend: function () {
        },
        success: function (response) {
          if (response == 1) {
            $('#sidebar-body').append('<div>Cambio Estado -> A "Pago Realizado".</div>');
            validarRGAdminTesoreria('Rendiciones en Tesorería', emailUsuario, 'En Tesorería', 'tesoreria');
            scrollToAnchorXid('card00');
            notifica('success', 'Estado cambiado a "En Tesorería" correctamente.');
            new calculaCantRGAdmin('En Contabilidad');
            new calculaCantRGAdmin('En Tesorería');
          } else {
            notifica('error', 'Algo Falló. Intente nuevamente.');
          }
        },
        complete: function (response) {
        },
        error: function (e) {
          notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
        }
      });
    } else {
    }
  });
}
/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
////////////////////  Personalizable   /////////////////////
/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
function cargaDashBoard() {
  parametros = {
    "type": 'cargaDashBoard',
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
      $('#opcion-title').html('Informaciones Generales');
    },
    success: function (response) {
      $('#opcion').html(response);
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function cargaMenu(idUsuario) {
  parametros = {
    "type": 'cargaMenu',
    "idUsuario": idUsuario,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
    },
    success: function (response) {
      $('#stacked-menu').html(response);
    },
    complete: function (response) {
      new StackedMenu();      
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function muestraAccesosUsuario(idUsuario, idModulo) {
  parametros = {
    "type": 'muestraAccesosUsuario',
    "idUsuario": idUsuario,
    "idModulo": idModulo,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
    },
    success: function (response) {
      $('#respuestaSVO_vxrutEscribe').html(response);
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function modalEditarDatosUsuarioSistema(modalTitle, tabla, idDeLaTabla, tipo) {
  parametros = {
    "type": 'modalEditarDatosUsuarioSistema',
    "tabla": tabla,
    "idDeLaTabla": idDeLaTabla,
    "tipo": tipo,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
      $('#modalGeneral').modal('toggle');
    },
    success: function (response) {
      $('#modalTitle').html(modalTitle);
      $('#modalBody').html(response);
      $('#modalTamano').attr('class', 'modal-dialog modal-lg');
      //$('#modalBtnGuardar').attr('onClick','editarDatosUsuarioSistemaEjecuta(\''+idDeLaTabla+'\',\''+tipo+'\');');
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function generaAccesosSistemaFinal(idUsuario) {
  parametros = {
    "type": 'generaAccesosSistemaFinal',
    "idUsuario": idUsuario,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () { },
    success: function (response) {
      if (response == 1) {
        notifica('success', "Accesos Creados!");
        //$('#modalGeneral').modal('toggle');
        //modalEditarDatosUsuarioSistema('Editar acceso usuario','credenciales',''+id+'','accesosUsuarios');
        credencialesInicio();
        scrollToAnchorXid('card00');
        asideEscondeMobile();
      }
      if (response == 0) {
        notifica('error', "Algo ha fallado.<br>Intente Nuevamente");
      }
    },
    complete: function (response) { },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function marcaTodos(idInput, btnTipo, idUsuario, nombreBoton) {
  var semaforoCheckbox = $(idInput).is(':checked');
  if (semaforoCheckbox == true) { var estadoNuevo = 1; $("[name='" + nombreBoton + "']").attr('checked', true); } else { estadoNuevo = 0; $("[name='" + nombreBoton + "']").attr('checked', false); }
  parametros = {
    "type": 'marcaTodos',
    "idInput": idInput,
    "btnTipo": btnTipo,
    "idUsuario": idUsuario,
    "estadoNuevo": estadoNuevo,
    "nombreBoton": nombreBoton,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () { },
    success: function (response) {
      if (response == 1) {
        notifica('success', "\xa0Se ha cambiado el Acceso.");
      }
      if (response == 0) {
        notifica('error', "Algo ha fallado.<br>Intente Nuevamente");
      }
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function listarJugadores(title, idUsuario, idModulo, countSubModulo, cantity) {
  $('#opcion').html('<div class="spinner-border text-primary" role="status"></div>');
  //muestraAccesosUsuario(idUsuario,idModulo);
  parametros = {
    "type": 'listarJugadores',
    'idUsuario': idUsuario,
    'idModulo': idModulo,
    'countSubModulo': countSubModulo,
    'cantity': cantity,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
      $('#opcion-title').html(title);
    },
    success: function (response) {
      $('#opcion').html(response);
    },
    complete: function (response) {
      ordenaSelectTodos();
      ordenaTabla('tablaAdministraJugadores', '0', 'asc');
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function formJugadores(title, id, tabla, tipo) {
  parametros = {
    "type": 'formJugadores',
    "id": id,
    "tabla": tabla,
    "tipo": tipo,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
    },
    success: function (response) {
      $('#opcion-title').html(title);
      $('#opcion').html(response);
      ordenaSelectTodos();
      if (tipo == 'editarJugadores') {
        $('#aseguradoIDContainer').attr('class', 'form-row d-none');
      }
      var aseguradoID = $('#aseguradoID').val();
      if (tipo == 'editarJugadores' && aseguradoID == '1') {
        listarJugadorSeguro(id, '');
      }
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function guardarEditaJugadores(idRecibido, tabla, opcion) {
  var nombreID          = $('#nombreID').val();
  var apellidoID        = $('#apellidoID').val();
  var tipoDocumentoID   = $('#tipoDocumentoID').val();
  var documentoID       = $('#documentoID').val();
  var fnacimientoID     = $('#fnacimientoID').val();
  var posicionID        = $('#posicionID').val();
  var nacionalidadID    = $('#nacionalidadID').val();
  var equipoID2         = $('#equipoID').select2('val');
  var equipoID          = JSON.stringify(equipoID2);

  var aseguradoID = $('#aseguradoID').val();
  var fotoID = $('#fotoID').val();
  if (fotoID == '' || fotoID == null) {
    fotoID = 'sinimagen300x300.png';
  }
  var emailID = $('#emailID').val(); //No Obligatorio
  var celularID = $('#celularID').val(); //No Obligatorio
  if (nombreID == '' || apellidoID == '' || tipoDocumentoID == '' || documentoID == '' || fnacimientoID == '' || posicionID == '' || nacionalidadID == '' || equipoID == '') {
    $('.needs-validation').addClass('was-validated');
    notifica('error', "Debe ingresar los campos requeridos.");
    return false;
  };

  parametros = {
    "type": 'guardarEditaJugadores',
    'nombreID': nombreID,
    'apellidoID': apellidoID,
    'tipoDocumentoID': tipoDocumentoID,
    'documentoID': documentoID,
    'fnacimientoID': fnacimientoID,
    'posicionID': posicionID,
    'equipoID': equipoID,
    'nacionalidadID': nacionalidadID,
    'emailID': emailID,
    'celularID': celularID,
    'aseguradoID': aseguradoID,
    'fotoID': fotoID,
    'idRecibido': idRecibido,
    'tabla': tabla,
    'opcion': opcion,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
    },
    success: function (response) {
      if (response == 0) {
        alerta('error', 'Ya existe un jugador con este numero de documento, por favor intente nuevamente.');
      } else {
        notifica(response.tipo, response.principal, response.mensaje);
        if(response.tipo == 'success'){
          $('#listarJugadores').click();
        }
      }
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function cambiaStateCheckbox(tipo, idChkBox, tabla, idLinea, nombreCampo) {
  var semaforoCheckbox = $(idChkBox).is(':checked');
  if (semaforoCheckbox == true) { var estadoNuevo = 1; } else { estadoNuevo = 0 }
  parametros = {
    "type": 'cambiaStateCheckbox',
    "estadoNuevo": estadoNuevo,
    "tipo": tipo,
    "idChkBox": idChkBox,
    "tabla": tabla,
    "idLinea": idLinea,
    "nombreCampo": nombreCampo,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () { },
    success: function (response) {
      if (response == 1) {
        if (tipo == 'cambiaAccesos') { notifica('success', "\xa0Se ha cambiado el Acceso."); }
      }
      if (response == 0) {
        notifica('error', "Algo ha fallado.<br>Intente Nuevamente");
      }
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function modalCambiaFoto(idRecibido, modalTitle, numFoto) {
  parametros = {
    "type": 'modalCambiaFoto',
    "idRecibido": idRecibido,
    "numFoto": numFoto,
  };
  jQuery.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    // dataType: "html",
    beforeSend: function () {
      $('#modalGeneral_otro').modal('toggle');
    },
    success: function (response) {
      $('#modalTitle_otro').html(modalTitle);
      $('#modalBody_otro').html(response);
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function guardaFotoCambiada(idRecibido, numFoto) {
  var fotoID = $('#inputNames #fotoID').val();
  if (fotoID == "" || fotoID == null) {
    notifica('error', 'Debe subir una foto antes de guardar.');
    return false;
  }
  parametros = {
    "type": 'guardaFotoCambiada',
    "idRecibido": idRecibido,
    "numFoto": numFoto,
    "fotoID": fotoID,
  };
  jQuery.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    // dataType: "html",
    beforeSend: function () {
    },
    success: function (response) {
      if (response == 1) {
        myDropzone.processQueue();
        notifica('success', 'La foto fue actualizada con éxito.');
        $('#modalGeneral_otro').modal('toggle');
        $("#imagesOld img").attr("src", "images/jugadores/" + fotoID);
        $("#imagesOld .figure-title").text(fotoID);
        $('#fotoID').val(fotoID);
        $('#eliminaFotoBtn').attr('onclick', 'eliminaFoto(\'' + idRecibido + '\',\'foto\',\'' + fotoID + '\')');
      }
      if (response != 1) {
        notifica('error', 'Ha ocurrido un error, por favor intente nuevamente.');
      }
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function eliminaFoto(idRecibido, numFoto, url) {
  if (url == "" || url == null) {
    notifica('error', 'No hay foto para eliminar.');
    return false;
  }
  Swal.fire({
    title: 'Eliminar',
    text: "Estás seguro que quieres eliminar?",
    type: 'question',
    showCancelButton: true,
    confirmButtonColor: '#B76BA3',
    cancelButtonColor: '',
    confirmButtonText: 'Sí',
    cancelButtonText: 'No'
  }).then((result) => {
    if (result.value == true) {
      parametros = {
        "type": 'eliminaFoto',
        "idRecibido": idRecibido,
        "numFoto": numFoto,
        "url": url,
      };
      jQuery.ajax({
        data: parametros,
        url: "option.php",
        type: "get",
        beforeSend: function () {
        },
        success: function (response) {
          if (response == 1) {
            notifica('success', 'La foto fue eliminada con éxito.');
            $("#imagesOld img").attr("src", "images/jugadores/sinimagen300x300.png");
            $("#imagesOld .figure-title").text('sinimagen300x300.png');
          }
          if (response == 0) {
            notifica('error', 'Ocurrió un problema. Intente nuevamente.', '');
          }
          if (response == 2) {
            notifica('warning', 'No fue posible borrar este imagen.');
          }
        },
        complete: function (response) {
        },
        error: function (e) {
          notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
        }
      });
    }
  })
}

function eliminaFotoGen(idRecibido, numFoto, url) {
  if (url == "" || url == null) {
    notifica('error', 'No hay foto para eliminar.');
    return false;
  }
  parametros = {
    "type": 'eliminaFoto',
    "idRecibido": idRecibido,
    "numFoto": numFoto,
    "url": url,
  };
  jQuery.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
    },
    success: function (response) {
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function formSumula(title) {
  parametros = {
    "type": 'formSumula',
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
    },
    success: function (response) {
      $('#opcion-title').html(title);
      $('#opcion').html(response);
    },
    complete: function (response) {
      ordenaSelectTodos();
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function generaSumula(modalTitle) {
  var equipo1SumID = $('#equipo1SumID').val();
  var equipo2SumID = $('#equipo2SumID').val();
  var fechaSumID = $('#fechaSumID').val();
  var jornadaSumID = $('#jornadaSumID').val();
  var canchaSumID = $('#canchaSumID').val();
  var competSumID = $('#competSumID option:selected').text();
  var horaPartidoSumID = $('#horaPartidoSumID').val();
  var tipoJornadaID = $('#tipoJornadaID').val();
  if (equipo1SumID == '' || equipo2SumID == '' || fechaSumID == '' || jornadaSumID == '' || canchaSumID == '' || competSumID == '' || horaPartidoSumID == '' || tipoJornadaID == '') {
    $('.needs-validation').addClass('was-validated');
    notifica('error', "Debe ingresar los campos requeridos.");
    return false;
  };
  parametros = {
    "type": 'generaSumula',
    "equipo1SumID": equipo1SumID,
    "equipo2SumID": equipo2SumID,
    "fechaSumID": fechaSumID,
    "jornadaSumID": jornadaSumID,
    "canchaSumID": canchaSumID,
    "competSumID": competSumID,
    "horaPartidoSumID": horaPartidoSumID,
    "tipoJornadaID": tipoJornadaID,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
      $('#modalGeneral').modal('toggle');
    },
    success: function (response) {
      $('#modalTamano').attr('class', 'modal-dialog modal-xl');
      $('#modalTitle').html(modalTitle);
      $('#modalBody').html(response);
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function listarCompeticiones(title, idUsuario, idModulo, countSubModulo) {
  $('#opcion').html('<div class="spinner-border text-primary" role="status"></div>');
  //muestraAccesosUsuario(idUsuario,idModulo);
  parametros = {
    "type": 'listarCompeticiones',
    'idUsuario': idUsuario,
    'idModulo': idModulo,
    'countSubModulo': countSubModulo,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
      $('#opcion-title').html(title);
    },
    success: function (response) {
      $('#opcion').html(response);
    },
    complete: function (response) {
      // ordenaTabla('tablaAdministraJugadores', '0', 'asc');
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function formCompeticiones(title, id, tabla, tipo) {
  parametros = {
    "type": 'formCompeticiones',
    "id": id,
    "tabla": tabla,
    "tipo": tipo,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
    },
    success: function (response) {
      $('#opcion-title').html(title);
      $('#opcion').html(response);
      ordenaSelectTodos();
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function guardarEditaCompeticiones(idRecibido, tabla, opcion) {
  var nombreCompet = $('#nombreCompetID').val();
  var edadMinimaCompet = $('#edadMinimaCompetID').val();
  if (nombreCompet == '') {
    $('.needs-validation').addClass('was-validated');
    notifica('error', "Debe ingresar los campos requeridos.");
    return false;
  };
  parametros = {
    "type": 'guardarEditaCompeticiones',
    'nombreCompet': nombreCompet,
    'edadMinimaCompet': edadMinimaCompet,
    'idRecibido': idRecibido,
    'tabla': tabla,
    'opcion': opcion,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
    },
    success: function (response) {
      if (response == 1) {
        notifica('success', 'Registro agregado con éxito.');
        $('#listarCompeticiones').click();
      }
      if (response == 0) {
        notifica('error', 'Algo ha fallado. Intente Nuevamente.');
      }
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function listarEquipos(title, idUsuario, idModulo, countSubModulo) {
  $('#opcion').html('<div class="spinner-border text-primary" role="status"></div>');
  //muestraAccesosUsuario(idUsuario,idModulo);
  parametros = {
    "type": 'listarEquipos',
    'idUsuario': idUsuario,
    'idModulo': idModulo,
    'countSubModulo': countSubModulo,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
      $('#opcion-title').html(title);
    },
    success: function (response) {
      $('#opcion').html(response);
    },
    complete: function (response) {
      ordenaTabla('tablaAdministraEquipos', '0', 'desc');
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function formEquipos(title, id, tabla, tipo) {
  parametros = {
    "type": 'formEquipos',
    "id": id,
    "tabla": tabla,
    "tipo": tipo,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
    },
    success: function (response) {
      $('#opcion-title').html(title);
      $('#opcion').html(response);
      ordenaSelectTodos();
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function guardarEditaEquipos(idRecibido, tabla, opcion) {
  var idEquipoUsuario = $('#equipoUsuario').val();
  var nombreEquipoID = $('#nombreEquipoID').val();
  var competicionID = $('#competicionID').val();
  if (nombreEquipoID == '' || competicionID == '') {
    $('.needs-validation').addClass('was-validated');
    notifica('error', "Debe ingresar los campos requeridos.");
    return false;
  };
  parametros = {
    "type": 'guardarEditaEquipos',
    'nombreEquipoID': nombreEquipoID,
    'competicionID': competicionID,
    'idRecibido': idRecibido,
    'idEquipoUsuario': idEquipoUsuario,
    'tabla': tabla,
    'opcion': opcion,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
    },
    success: function (response) {
      if (response == 1) {
        notifica('success', 'Registro agregado con éxito.', '');
        $('#listarEquipos').click();
      }
      if (response == 0) {
        notifica('error', 'Algo ha fallado. Intente Nuevamente.');
      }
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function consultasDB(title) {
  $('#opcion').html('<div class="spinner-border text-primary" role="status"></div>');
  parametros = {
    "type": 'consultasDB',
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
      $('#opcion-title').html(title);
    },
    success: function (response) {
      $('#opcion').html(response);
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function reglasNegocio(title) {
  $('#opcion').html('<div class="spinner-border text-primary" role="status"></div>');
  parametros = {
    "type": 'reglasNegocio',
  };
  $.ajax({
    data: parametros,
    url: "reglas-negocio.php",
    type: "get",
    beforeSend: function () {
      $('#opcion-title').html(title);
    },
    success: function (response) {
      $('#opcion').html(response);
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function pintaSuspendido(countLinea, num) {
  if (num == 1) {
    $('#nombre1_' + countLinea).attr('style', 'text-decoration: line-through;font-weight: bold;');
    $('#firma1_' + countLinea).html('<span class="font-weight: bold;">SUSPENDIDO</span>');
    $('#tr1_' + countLinea).attr('style', 'background-color: #a9a9a9;');
  }
  if (num == 2) {
    $('#nombre2_' + countLinea).attr('style', 'text-decoration: line-through;font-weight: bold;');
    $('#firma2_' + countLinea).html('<span class="font-weight: bold;">SUSPENDIDO</span>');
    $('#tr2_' + countLinea).attr('style', 'background-color: #a9a9a9;');
  }
}

function reportesSeguros(title, numReport) {
  $('#opcion').html('<div class="spinner-border text-primary" role="status"></div>');
  parametros = {
    "type": 'reportesSeguros',
    'numReport': numReport,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
      $('#opcion-title').html(title);
    },
    success: function (response) {
      $('#opcion').html(response);
    },
    complete: function (response) {
      ordenaTabla('tablaReportes', '0', 'asc');
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function refrescaEquipos() {
  var id_compet = $('#competSumID').val();
  $('#equipo1SumID').html("");
  $('#equipo2SumID').html("");
  parametros = {
    "type": 'refrescaEquipos',
    "id_compet": id_compet,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
    },
    success: function (response) {
      $('#equipo1SumID').append(response);
      $('#equipo2SumID').append(response);
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function formJugadorSeguro(modalTitle, idRecibido, idJugador, opcion) {
  parametros = {
    "type": 'formJugadorSeguro',
    "idRecibido": idRecibido,
    "idJugador": idJugador,
    "opcion": opcion,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
      $('#modalGeneral_otro').modal('toggle');
    },
    success: function (response) {
      $('#modalTitle_otro').html(modalTitle);
      $('#modalBody_otro').html(response);
      $('#modalTamano_otro').attr('class', 'modal-dialog modal-lg');
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function guardarSeguroJugador(idRecibido, tabla, opcion) {
  var inputNuevo_idJugador           = $('#inputNuevo_idJugador').val();
  var inputNuevo_siniestro           = $('#inputNuevo_siniestro').val();
  var inputNuevo_valorPagado         = $('#inputNuevo_valorPagado').val();
  var inputNuevo_fechaPagoSeguro     = $('#inputNuevo_fechaPagoSeguro').val();
  var inputNuevo_fechaLesion         = $('#inputNuevo_fechaLesion').val();
  var inputNuevo_jornadaLesion       = $('#inputNuevo_jornadaLesion').val();
  var inputNuevo_competicionLesion   = $('#inputNuevo_competicionLesion').val();
  var inputNuevo_comentarios         = $('#inputNuevo_comentarios').val();
  var d = new Date();
  var strDate = d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate();

  if (inputNuevo_siniestro == '' || inputNuevo_valorPagado == '' || inputNuevo_fechaPagoSeguro == '' || inputNuevo_fechaPagoSeguro > strDate || inputNuevo_fechaLesion == '' || inputNuevo_jornadaLesion == '' || inputNuevo_comentarios == '' || inputNuevo_competicionLesion == '' ) {
      $('.needs-validation').addClass('was-validated');
      if(inputNuevo_fechaPagoSeguro > strDate) {
        notifica('error', "La fecha de pago seguro debe ser menor o igual que el día de hoy.");
      } else {
        notifica('error', "Debe ingresar los campos requeridos.");
      }
      return false;
  };

  parametros = {
      "type"                           : 'guardarSeguroJugador',
      'idRecibido'                     : idRecibido,
      'inputNuevo_idJugador'           : inputNuevo_idJugador,
      'inputNuevo_siniestro'           : inputNuevo_siniestro,
      'inputNuevo_valorPagado'         : inputNuevo_valorPagado,
      'inputNuevo_fechaPagoSeguro'     : inputNuevo_fechaPagoSeguro,
      'inputNuevo_fechaLesion'         : inputNuevo_fechaLesion,
      'inputNuevo_jornadaLesion'       : inputNuevo_jornadaLesion,
      'inputNuevo_competicionLesion'   : inputNuevo_competicionLesion,
      'inputNuevo_comentarios'         : inputNuevo_comentarios,
      'tabla'                          : tabla,
      'opcion'                         : opcion,
  };
  $.ajax({
      data: parametros,
      url: "option.php",
      type: "get",
      beforeSend: function () {
      },
      success: function (response) {
        if (response == 1) {
          if(opcion == 'agregar'){
            notifica('success', 'Registro agregado con éxito.', '');
          } else if (opcion == 'editar'){
            notifica('success', 'Registro actualizado con éxito.', '');
          }
          $('#modalGeneral_otro').modal('toggle');
        } else if (response == 0) {
          notifica('error', 'Algo ha fallado. Intente Nuevamente.');
        }
      },
      complete: function (response) {
        $('#listarJugadoresSeguro').click();
      },
      error: function (e) {
          alert(e);
      }
  });
}

function listarJugadorSeguro(idRecibido, dato) {
  parametros = {
    "type": 'listarJugadorSeguro',
    "idRecibido": idRecibido,
    "dato": dato,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
    },
    success: function (response) {
      $('#containerJugadoresSeguros').html(response);
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function habilitaGuardarAsegurado(id) {
  $('#btnEditarAsegurado_' + id).attr('style', 'display: none;');
  $('#btnGuardarAsegurado_' + id).attr('style', 'display: initial;');
  $('#aseguradoValue_' + id).attr('disabled', false);
}

function guardarAseguradoDato(idRecibido) {
  var asegurado = $('#aseguradoValue_' + idRecibido).val();
  parametros = {
    "type": 'guardarAseguradoDato',
    "asegurado": asegurado,
    "idRecibido": idRecibido,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
    },
    success: function (response) {
      if (response == 1) {
        notifica('success', 'Registro actualizado con éxito.');
        $('#aseguradoValue_' + idRecibido).attr('disabled', true);
        $('#btnEditarAsegurado_' + idRecibido).attr('style', 'display:initial;');
        $('#btnGuardarAsegurado_' + idRecibido).attr('style', 'display:none;');

      } else {
        notifica('error', 'Hubo un error, por favor contacte a un administrador.');
      }
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function opcionesSeguro(title, dato) {
  parametros = {
    "type": 'opcionesSeguro',
    "dato": dato,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
      $('#opcion-title').html(title);
    },
    success: function (response) {
      $('#opcion').html(response);
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function formOpcSeguros(title, id, tabla, tipo) {
  parametros = {
    "type": 'formOpcSeguros',
    "id": id,
    "tabla": tabla,
    "tipo": tipo,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
    },
    success: function (response) {
      $('#opcion-title').html(title);
      $('#opcion').html(response);
      ordenaSelectTodos();
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function guardarEditaOpcSeguros(idRecibido, tabla, opcion) {
  var tipoID = $('#tipoID').val();
  var valorID = $('#valorID').val();

  if (tipoID == '' || valorID == '') {
    $('.needs-validation').addClass('was-validated');
    notifica('error', "Debe ingresar los campos requeridos.");
    return false;
  };
  parametros = {
    "type": 'guardarEditaOpcSeguros',
    'tipoID': tipoID,
    'valorID': valorID,
    'idRecibido': idRecibido,
    'tabla': tabla,
    'opcion': opcion,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
    },
    success: function (response) {
      if (response == 1) {
        notifica('success', 'Registro agregado con éxito.', '');
        $('#opcSeguros').click();
      }
      if (response == 0) {
        notifica('error', 'Algo ha fallado. Intente Nuevamente.');
      }
    },
    complete: function (response) {
    },
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

/* Radio Multiples */
var TableDemo = /*#__PURE__*/function () {
  function TableDemo() {
    _classCallCheck(this, TableDemo);

    this.init();
  }

  _createClass(TableDemo, [{
    key: "init",
    value: function init() {
      // event handlers
      this.handleSelecter();
    }
  }, {
    key: "handleSelecter",
    value: function handleSelecter() {
      var self = this;
      $(document).on('change', '#check-handle', function () {
        var isChecked = $(this).prop('checked');
        $('input[name="jugadoresCheck"]').prop('checked', isChecked); // get info

        self.getSelectedInfo();
      }).on('change', 'input[name="jugadoresCheck"]', function () {
        var $selectors = $('input[name="jugadoresCheck"]');
        var $selectedRow = $('input[name="jugadoresCheck"]:checked').length;
        var prop = $selectedRow === $selectors.length ? 'checked' : 'indeterminate'; // reset props

        $('#check-handle').prop('indeterminate', false).prop('checked', false);

        if ($selectedRow) {
          $('#check-handle').prop(prop, true);
        } // get info


        self.getSelectedInfo();
      });
    }
  }, {
    key: "getSelectedInfo",
    value: function getSelectedInfo() {
      var $selectedRow = $('input[name="jugadoresCheck"]:checked').length;
      var $info = $('.thead-btn');
      var $badge = $('<span/>').addClass('selected-row-info text-muted pl-1').text("".concat($selectedRow)); // remove existing info

      $('.selected-row-info').remove(); // add current info

      if ($selectedRow) {
        $info.prepend($badge);
      }
    }
  }, {
    key: "clearSelectedRows",
    value: function clearSelectedRows() {
      $('#check-handle').prop('indeterminate', false).prop('checked', false).trigger('change');
    }
  }]);

  return TableDemo;
}();

$(document).on('theme:init', function () {
  new TableDemo();
});

function eImgNoUsadas() {
  parametros = {
    "type": 'eImgNoUsadas',
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    error: function (e) {
      notifica('error', 'Ocurrió un error, por favor intente nuevamente o contacte el aministrador del sistema.');
    }
  });
}

function importarDB(title) {
  $("#opcion").html(
    '<div class="spinner-border text-primary" role="status"></div>'
  );
  parametros = {
    type: "importarDB",
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
      $("#opcion-title").html(title);
    },
    success: function (response) {
      $("#opcion").html(response);
    },
    complete: function (response) {},
    error: function (e) {
      alerta(
        "error",
        "Error de Sistema",
        "Hubo un error inesperado, intente nuevamente."
      );
    },
  });
}

function cargaMasiva(tabla) {
  parametros = {
    "type": "cargaMasiva",
    "tabla": tabla,
  };
  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {
    },
    success: function (response) {
      $("#modalTamano").attr("class", "modal-dialog modal-xl");
      $("#modalTitle").html('Carga Masiva de ' + tabla);
      $("#modalBody").html(response);
      $("#modalGeneral").modal("toggle");
    },
    complete: function (response) {
      Dropzone.options.importaArchivosAbd = {
        acceptedFiles: ".xlsx",
        accept: function (file, done) {
          $("#btnMuestraArchivoImportado").attr("onclick", "muestraArchivoImportado('" + file.name + "','Archivo Importado','importaArchivosAbd');").attr("style", 'display: block;');
          done();
        },
      };
      $(".dropzone").dropzone({
        dictDefaultMessage: "<h5>Haga clic o Arrastre aquí su archivo</h5>",
        dictInvalidFileType: "Extensión Incorrecta",
      });
    },
    error: function (e) {
      alerta("error","Error de Sistema","Hubo un error inesperado, intente nuevamente.");
    },
  });
}

function muestraArchivoImportado(archivo, modalTitle, nombreForm) {
  var tablaImportar = $("#tablaImportar").val();
  if (tablaImportar == null) {
    notifica("error", "Error", "Debe seleccionar una tabla valida.");
    return false;
  }

  parametros = {
    "type" : "muestraArchivoImportado",
    "archivo" : archivo,
    "tablaImportar" : tablaImportar,
  };

  $.ajax({
    data: parametros,
    url: "option.php",
    type: "get",
    beforeSend: function () {},
    success: function (response) {
      $("#modalTitle").html(modalTitle);
      $("#modalBody").html(response);
    },
    complete: function (response) {
      $("#btnMuestraArchivoImportado").attr("style", 'display: none;');
    },
    error: function (e) {
      alerta("error","Error de Sistema","Hubo un error inesperado, intente nuevamente.");
    },
  });
}