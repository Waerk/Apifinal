<?php
class ContactoController extends Controller
{
    /**
     * Carga la vista con el formulario de contacto
     * 
     * @return ViewResponse
     */
    public function index()
    {
        return view('contacto');
    }


    // ---- FUNCIÓN PARA ENVIAR MAIL -----
    /**
     * Envía el email al administrador de la aplicación
     * @return RedirectResponse
     */
    public function send()
    {
        if (empty(request()->post('enviar'))) {
            throw new FormException('No se recibió el formulario de contacto.');
        }

        // Toma los datos del formulario de contacto
        $from = request()->post('email');
        $name = request()->post('nombre');
        $subject = request()->post('asunto');
        $message = request()->post('mensaje');

        // Intenta preparar y enviar el email al administrador
        // cuyo email está configurado en el fichero config/config.php
        try {
            $email = new Email(ADMIN_EMAIL, $from, $name, $subject, $message);
            $email->send();

            // Flash: muestra el mensaje de éxito y redirige a la portada
            Session::success("Mensaje enviado, en breve recibirás una respuesta.");
            return redirect('/');
        } catch (EmailException $e) {
            Session::error("No se pudo enviar el email.");
            if (DEBUG) {
                throw new Exception($e->getMessage());
            }
            return redirect('/Contacto');
        }
    }
}