// Función global para mostrar notificaciones
function showNotification(message, isSuccess) {
    const notificationContainer = $('#notification-container');
    const notification = $(`
        <div class="alert ${isSuccess ? 'alert-success' : 'alert-danger'}" role="alert">
            ${message}
        </div>
    `);
    notificationContainer.append(notification);

    // Eliminar la notificación automáticamente después de 5 segundos
    setTimeout(() => {
        notification.fadeOut(500, () => notification.remove());
    }, 1000);
}

// Reemplazar console.log con notificaciones visuales
const originalConsoleLog = console.log;
const originalConsoleError = console.error; 

console.log = function (message) {
    showNotification(message, true); // Mensaje exitoso en verde
    originalConsoleLog.apply(console, arguments); // Mantener funcionalidad original
};

console.error = function (message) {
    showNotification(message, false); // Mensaje de error en rojo
    originalConsoleError.apply(console, arguments); // Mantener funcionalidad original
};
$(document).ready(function () {
    // Cargar los modales dinámicamente
    $('#modales').load('/dgp/views/admin_modales.html', function () {
        //console.log('Modales cargados en el dashboard.');

           // Registrar Estudiante
        $('#registerStudentForm').on('submit', function (e) {
            e.preventDefault();

            const passwordField = $('#studentPassword');
            const password = passwordField.val();

            // Validar que la contraseña sea numérica y de máximo 6 caracteres
            if (!/^\d{1,6}$/.test(password)) {
                showNotification('La contraseña debe ser numérica y de máximo 6 caracteres.', false);
                passwordField.focus();
                return;
            }

            const formData = new FormData(this); // Usar FormData para manejar archivos

            $.ajax({
                async: true,
                url: '/dgp/ajax/register_student.php',
                method: 'POST',
                data: formData,
                processData: false, // Evitar procesamiento automático de datos
                contentType: false, // No establecer ningún tipo de contenido explícito
                success: function (response) {
                    try {
                        const res = JSON.parse(response); // Intentar parsear el JSON
                        if (res.success) {
                            showNotification(res.message || 'Estudiante registrado exitosamente.', true);
                            $('#registerStudentModal').modal('hide');
                            // Opcional: Resetear el formulario
                            $('#registerStudentForm')[0].reset();
                        } else {
                            showNotification(res.message || 'Error al registrar el estudiante.', false);
                        }
                    } catch (error) {
                        showNotification('Error inesperado al procesar la respuesta del servidor.', false);
                        console.error('Error al parsear JSON:', response, error);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    showNotification('Error en el servidor: ' + textStatus, false);
                    console.error('Error en el servidor:', errorThrown);
                }
            });
        });

// Registrar Profesor
        $('#registerTeacherForm').on('submit', function (e) {
            e.preventDefault();

            const passwordField = $('#teacherPassword');
            const password = passwordField.val().trim();

    // Validar que la contraseña sea segura
            const securePasswordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};:"\\|,.<>\/?]).{8,20}$/;

            if (!securePasswordRegex.test(password)) {
                showNotification('La contraseña debe tener entre 8 y 20 caracteres, incluir al menos una letra mayúscula, una letra minúscula, un número y un símbolo especial.', false);
                passwordField.focus();
                return;
            }

    const formData = new FormData(this); // Usar FormData para manejar archivos

    $.ajax({
        async: true,
        url: '/dgp/ajax/register_teacher.php',
        method: 'POST',
        data: formData,
        processData: false, // Evitar procesamiento automático de datos
        contentType: false, // No establecer ningún tipo de contenido explícito
        success: function (response) {
            try {
                const res = JSON.parse(response); // Intentar parsear el JSON
                if (res.success) {
                    showNotification(res.message || 'Profesor registrado exitosamente.', true);
                    $('#registerTeacherModal').modal('hide');
                    $('#registerTeacherForm')[0].reset(); // Resetear el formulario
                } else {
                    showNotification(res.message || 'Error al registrar el profesor.', false);
                }
            } catch (error) {
                showNotification('Error inesperado al procesar la respuesta del servidor.', false);
                console.error('Error al parsear JSON:', response, error);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            showNotification('Error en el servidor: ' + textStatus, false);
            console.error('Error en el servidor:', errorThrown);
        }
    });
});


    });
});


