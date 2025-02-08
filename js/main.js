$(document).ready(function () {
    // Detectar el parámetro 'view' en la URL
    const urlParams = new URLSearchParams(window.location.search);
    const view = urlParams.get('view');

    // Si hay un parámetro de vista, cargar la correspondiente
    if (view) {
        loadViewByQuery(view);
    }

    $(document).on('click', '.section', function (e) {
        // Verificar si el clic ocurrió en el botón del micrófono
        if ($(e.target).is('#startVoiceRecognition')) {
            e.stopPropagation(); // Evita que el evento se propague al contenedor
            return; // Salimos para que no redirija a otra página
        }

        const target = $(this).data('target');
        if (target === 'adminLogin') {
            window.location.href = '/dgp/index.php?view=adminLogin';
        } else if (target === 'profesorLogin') {
            window.location.href = '/dgp/index.php?view=profesorLogin';
        } else if (target === 'estudianteLogin') {
            loadView('/dgp/views/estudiante_login.html');
        }else if (target === 'listaEstudiantes') {
            loadView('/dgp/views/lista_estudiantes.html');
        }
    });
    // Iniciar sesión por voz
    $(document).on('click', '#startVoiceRecognition', function (e) {
        e.preventDefault(); // Previene la acción predeterminada
        e.stopPropagation(); // Evita que el clic se propague al contenedor padre

        // Aquí puedes implementar la lógica para iniciar sesión por voz
        setupVoiceRecognition();
    });


    // Configurar eventos para formularios
    setupLoginEvents();

    $(document).on('submit', '#adminLoginForm, #profesorLoginForm', function (e) {
        e.preventDefault();
        const form = $(this);
        const tipo = form.find('input[name="tipo"]').val();

        $.ajax({
            url: '/dgp/ajax/login.php',
            method: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                // Redirigir al dashboard del administrador o profesor
                    if (tipo === 'admin') {
                        window.location.href = '/dgp/php/admin_dashboard.php';
                    } else if (tipo === 'profesor') {
                        window.location.href = '/dgp/php/profesor_dashboard.php';
                    }
                } else {
                    showNotification(response.message, false);
                }
            },
            error: function () {
                showNotification('Error en el servidor. Por favor, inténtelo de nuevo.', false);
            }
        });
    });

});

function loadView(url) {
    const appContainer = $('#app-container');

    // Limpia completamente el contenedor antes de cargar la nueva vista
    appContainer.empty();
    appContainer.html('<div class="loading-spinner text-center"><p>Cargando...</p></div>');

    $.ajax({
        url: url,
        method: 'GET',
        success: function (data) {
            appContainer.html(data); // Reemplaza completamente el contenido

            // Configurar eventos y lógica específicos de la vista
            if (url.includes('estudiante_login.html')) {
                setupPictograms();
                //setupVoiceRecognition();
            }
            setupLoginEvents();
        },
        error: function () {
            appContainer.html('<div class="error-message text-center text-danger">Error al cargar la vista. Por favor, inténtelo de nuevo.</div>');
            showNotification('Error al cargar la vista. Por favor, inténtelo de nuevo.', false);
        }
    });
}



// Función para cargar vista por el parámetro de la URL
function loadViewByQuery(view) {
    const viewUrl = getViewUrl(view);
    if (viewUrl) {
        loadView(viewUrl); // Solo cargamos si hay una URL válida
    } else {
        // Mostramos un mensaje de error en el contenedor principal
        const appContainer = $('#app-container');
        appContainer.html('<div class="alert alert-danger" role="alert">Vista no encontrada. Por favor, inténtelo de nuevo.</div>');
    }
}

// Función para obtener la URL de la vista basada en el nombre
function getViewUrl(viewName) {
    switch (viewName) {
        case 'adminLogin':
            return '/dgp/views/admin_login.html';
        case 'profesorLogin':
            return '/dgp/views/profesor_login.html';
        case 'listaEstudiantes':
            return '/dgp/views/lista_estudiantes.html';
        case 'estudianteLogin':
            return '/dgp/views/estudiante_login.html';
        default:
            // Si no se reconoce la vista, mostramos un mensaje de error
            showNotification('Vista no encontrada. Por favor, inténtelo de nuevo.', false);
            return ''; // No redirigimos a ninguna vista
    }
}

// Función global para mostrar notificaciones
    function showNotification(message, isSuccess) {
        const notificationContainer = $('#notification-container');
    notificationContainer.empty(); // Limpiar las notificaciones anteriores para evitar duplicados
    const notification = $(
`<div class="alert ${isSuccess ? 'alert-success' : 'alert-danger'}" role="alert">${message}</div>`
);
    notificationContainer.append(notification);

    // Eliminar la notificación automáticamente después de 5 segundos
    setTimeout(() => {
        notification.fadeOut(500, () => notification.remove());
    }, 5000);
}

$(document).ready(function () {
    // Asegúrate de que el contenedor de notificaciones esté cargado
    if (!$('#notification-container').length) {
        $('body').append('<div id="notification-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>');
    }

    /*if (window.location.search.includes('view=estudianteLogin')) {
        $('#modales').load('/dgp/views/estudiante_login.html', function () {
            setupLoginEvents();
            setupPictograms();
            setupVoiceRecognition();
        });
    }*/

});


function setupLoginEvents() {
    // Desasociar cualquier evento existente antes de asociar nuevos eventos
    $(document).off('submit', '#estudianteLoginForm');

    // Asociar el evento al formulario de login del estudiante
    $(document).on('submit', '#estudianteLoginForm', function (e) {
        e.preventDefault();

        const form = $(this);
        const submitButton = form.find('button[type="submit"]');
        const username = $('#estudiante_nombre_usuario').val();
        const password = $('#selected-password').text();

        // Evitar que el botón de submit se pueda presionar varias veces
        submitButton.prop('disabled', true);

        if (!username || !password) {
            showNotification('Por favor, introduce un nombre de usuario y selecciona una contraseña.', false);
            submitButton.prop('disabled', false);
            return;
        }

        // Enviar los datos al servidor mediante Ajax
        $.ajax({
            url: '/dgp/ajax/login.php',
            method: 'POST',
            data: {
                nombre_usuario: username,
                password: password,
                tipo: 'estudiante'
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    // Redirigir al dashboard del estudiante
                    window.location.href = '/dgp/php/estudiante_dashboard.php';
                } else {
                    // Mostrar notificación personalizada
                    showNotification(response.message, false);
                }
            },
            error: function () {
                // Manejo de errores del servidor
                showNotification('Error en el servidor. Por favor, inténtelo de nuevo.', false);
            },
            complete: function () {
                // Rehabilitar el botón de submit
                submitButton.prop('disabled', false);
            }
        });
    });
}

// Manejo del login para todos los formularios
function handleLogin(form, expectedRole) {
    const submitButton = form.find('button[type="submit"]');
    submitButton.prop('disabled', true);

    $.ajax({
        url: '/dgp/ajax/login.php',
        method: 'POST',
        data: form.serialize(),
        dataType: 'json',
        success: function (response) {
            if (response.success) {
                const redirectUrl = response.redirect || '';
                const roleFromRedirect = redirectUrl.includes('admin')
                ? 'admin'
                : redirectUrl.includes('estudiante')
                ? 'estudiante'
                : redirectUrl.includes('profesor')
                ? 'profesor'
                : '';

                if (roleFromRedirect === expectedRole) {
                    window.location.href = redirectUrl; // Redirigir directamente al dashboard
                } else {
                    showNotification('Error: Tipo de usuario incorrecto para este formulario.', false);
                }
            } else {
                showNotification(response.message, false); // Mostrar mensaje de error del servidor
            }
        },
        error: function () {
            showNotification('Error en el servidor. Por favor, inténtelo de nuevo.', false);
        },
        complete: function () {
            submitButton.prop('disabled', false); // Reactivar el botón
        }
    });
}


// Función para generar los pictogramas dinámicamente
function setupPictograms() {
    const pictograms = [
        { id: 1, src: '/dgp/images/picto1.jpg', number: 1 },
        { id: 2, src: '/dgp/images/picto2.jpg', number: 2 },
        { id: 3, src: '/dgp/images/picto3.jpg', number: 3 },
        { id: 4, src: '/dgp/images/picto4.jpg', number: 4 },
        { id: 5, src: '/dgp/images/picto5.jpg', number: 5 },
        { id: 6, src: '/dgp/images/picto6.jpg', number: 6 }
    ];

    // Barajar pictogramas aleatoriamente
    const shuffledPictograms = [...pictograms].sort(() => Math.random() - 0.5);

    const pictogramContainer = $('#pictogram-container');
    const pictogramLegend = $('#pictogram-legend');
    const selectedPassword = [];
    const passwordPreview = $('#selected-password');

    // Limpiar contenedor y leyenda antes de agregar pictogramas
    pictogramContainer.empty();
    pictogramLegend.empty();

    // Generar leyenda en orden fijo
    pictograms.forEach(picto => {
        pictogramLegend.append(`
            <li class="d-flex align-items-center me-3">
                <span class="me-2">${picto.number}</span>
                <img src="${picto.src}" alt="Pictograma ${picto.number}" style="width: 40px; height: 40px;">
            </li>
        `);
    });

    // Mostrar pictogramas en orden aleatorio
    shuffledPictograms.forEach(picto => {
        pictogramContainer.append(`
            <button type="button" class="pictogram-btn" data-id="${picto.number}">
                <img src="${picto.src}" alt="Pictograma ${picto.number}" class="img-thumbnail" style="width: 100px; height: 100px;">
            </button>
        `);
    });

    // Evitar registrar eventos duplicados
    $(document).off('click', '.pictogram-btn').on('click', '.pictogram-btn', function () {
        const pictoId = $(this).data('id');
        selectedPassword.push(pictoId);
        updatePasswordPreview();
    });

    $(document).off('click', '#delete-last-pictogram').on('click', '#delete-last-pictogram', function () {
        if (selectedPassword.length > 0) {
            selectedPassword.pop();
            updatePasswordPreview();
        } else {
            showNotification('No hay pictogramas seleccionados para eliminar.', false);
        }
    });

    $(document).off('click', '#clear-password').on('click', '#clear-password', function () {
        selectedPassword.length = 0;
        updatePasswordPreview();
    });

    function updatePasswordPreview() {
        passwordPreview.text(selectedPassword.join(''));
    }

    $(document).off('click', '#submit-password').on('click', '#submit-password', function () {
        if (selectedPassword.length === 0) {
            showNotification('Por favor, selecciona al menos un pictograma.', false);
            return;
        }

        const username = $('#estudiante_nombre_usuario').val();
        const password = selectedPassword.join('');

        if (!username) {
            showNotification('Por favor, introduce tu nombre de usuario.', false);
            return;
        }

        $.ajax({
            url: '/dgp/ajax/login.php',
            method: 'POST',
            data: {
                nombre_usuario: username,
                password: password,
                tipo: 'estudiante',
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    // Redirigir directamente al dashboard
                    window.location.href = '/dgp/php/estudiante_dashboard.php';
                } else {
                    showNotification(response.message, false);
                }
            },
            error: function () {
                showNotification('Error en el servidor. Intente nuevamente.', false);
            }
        });
    });
}


// Función para iniciar sesión por voz
function setupVoiceRecognition() {
    let recognitionActive = false;
    let step = 1; // 1: Usuario, 2: Contraseña
    let username = '';
    let password = '';

    if ('webkitSpeechRecognition' in window) {
        const recognition = new webkitSpeechRecognition();
        recognition.lang = 'es-ES';
        recognition.continuous = false;
        recognition.interimResults = false;

        // Función que inicia el reconocimiento
        function startRecognition() {
            if (recognitionActive) {
                console.log("El reconocimiento ya está en ejecución. No se reiniciará.");
                return;
            }

            recognitionActive = true; // Marcar como activo antes de iniciar
            recognition.start();

            // Mostrar notificación según el paso
            if (step === 1) {
                showNotification('Por favor, diga su nombre de usuario.', true);
                speakText('Por favor, diga su nombre de usuario.');
            } else if (step === 2) {
                showNotification('Por favor, diga su contraseña.', true);
                speakText('Por favor, diga su contraseña.');
            }
        }

        // Manejo de eventos de reconocimiento
        recognition.onstart = function () {
            console.log("Reconocimiento de voz en progreso...");
        };

        recognition.onresult = function (event) {
            if (event.results.length > 0) {
                let transcript = event.results[0][0].transcript.trim().toLowerCase();

                // Validar para ignorar entradas que coincidan con el texto hablado
                if (transcript === 'por favor, diga su nombre de usuario' || transcript === 'por favor, diga su contraseña') {
                    console.log('Entrada ignorada porque coincide con el texto hablado.');
                    return;
                }

                console.log("Texto reconocido:", transcript);

                if (step === 1) {
                    username = transcript;
                    showNotification(`Nombre de usuario reconocido: ${username}`, true);
                    step = 2;
                    setTimeout(startRecognition, 1000); // Pasar a la contraseña
                } else if (step === 2) {
                    password = convertWordsToNumbers(transcript);
                    showNotification(`Contraseña reconocida: ${password}`, true);
                    recognitionActive = false;
                    handleVoiceLogin(username, password);
                }
            }
        };

        recognition.onspeechend = function () {
            console.log("Usuario dejó de hablar.");
            recognition.stop(); // Detener el reconocimiento después de la pausa
        };

        recognition.onend = function () {
            console.log("Reconocimiento finalizado.");
            recognitionActive = false; // Resetear estado
        };

        recognition.onerror = function (event) {
            console.error("Error de reconocimiento:", event.error);
            showNotification('Error de reconocimiento: ' + event.error, false);
            recognitionActive = false; // Resetear estado
        };

        $('#startVoiceRecognition').off('click').on('click', function () {
            startRecognition();
        });

        // Función para sintetizar voz
        function speakText(text) {
            const speech = new SpeechSynthesisUtterance();
            speech.lang = 'es-ES';
            speech.text = text;
            speech.volume = 0.8; // Reducir el volumen del texto hablado

            // Detener el reconocimiento mientras se reproduce el audio
            recognitionActive = false;
            recognition.stop();

            speech.onend = () => {
                console.log("Texto hablado finalizado. Reanudando reconocimiento...");
                setTimeout(() => {
                    recognitionActive = true; // Reanudar el reconocimiento
                    recognition.start(); // Iniciar nuevamente el reconocimiento de voz
                }, 500); // Retraso de 500ms entre voz y reconocimiento para evitar solapamientos
            };

            window.speechSynthesis.speak(speech);
        }
    } else {
        showNotification('Tu navegador no soporta reconocimiento por voz.', false);
    }

    function handleVoiceLogin(username, password) {
        if (!username || !password) {
            showNotification('Por favor, proporcione un usuario y una contraseña válidos.', false);
            return;
        }

        console.log("Enviando datos al servidor:", { username, password });

        $.ajax({
            url: '/dgp/ajax/login.php', // Ruta del backend para validar el login
            method: 'POST',
            data: {
                nombre_usuario: username,
                password: password,
                tipo: 'estudiante' // Tipo de usuario: estudiante
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    console.log("Inicio de sesión exitoso. Redirigiendo...");
                    window.location.href = '/dgp/php/estudiante_dashboard.php'; // Redirigir al dashboard
                } else {
                    console.log("Error en el inicio de sesión:", response.message);
                    showNotification(response.message, false);
                }
            },
            error: function () {
                console.error("Error en la solicitud AJAX.");
                showNotification('Error en el servidor. Por favor, inténtelo de nuevo.', false);
            }
        });
    }

    function convertWordsToNumbers(text) {
        const numberWords = {
            uno: '1', dos: '2', tres: '3', cuatro: '4', cinco: '5',
            seis: '6', siete: '7', ocho: '8', nueve: '9', cero: '0'
        };
        return text.split(/\s+/).map(word => numberWords[word.toLowerCase()] || word).join('');
    }
}


