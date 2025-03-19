<?php
class UserController extends Controller
{


    public function home()
    {
        Auth::check();
        return view('user/home', ['user' => Login::user()]);
    }

    // Acción para que los visitantes se registren
    public function register()
    {
        if (Login::user() !== null) {
            Session::error("Ya estás autenticado. Cierra sesión para registrar un nuevo usuario.");
            return redirect('/user/home');
        }
        return view('user/register');
    }

    public function storeRegister()
    {
        if (!request()->has('guardar')) {
            throw new FormException('No se recibió el formulario');
        }
        $user = new User();
        $user->displayname = request()->post('displayname');
        $user->email = request()->post('email');
        $user->phone = request()->post('phone');
        $user->poblacion = request()->post('poblacion');
        $user->cp = request()->post('cp');
        $user->password = request()->post('password');

        $user->roles = [];
        $user->addRole('ROLE_USER');

        try {
            if ($errores = $user->validate(false, false, USER_ROLES)) {
                throw new ValidationException(
                    "<br>" . arrayToString($errores, false, false, ".<br>")
                );
            }

            $user->password = md5($user->password);
            $user->saneate();
            $user->save();

            $file = request()->file('picture', 8000000, ['image/png', 'image/jpeg', 'image/gif', 'image/webp']);
            if ($file) {
                $user->picture = $file->store('../public/' . USER_IMAGE_FOLDER, 'user_');
                $user->update();
            }

            $email = new Email(
                $user->email,
                ADMIN_EMAIL,
                APP_NAME,
                'Bienvenido a ' . APP_NAME,
                "Hola $user->displayname,<br><br>" .
                    "Tu cuenta ha sido creada exitosamente en " . APP_NAME . ".<br>" .
                    "Tu email registrado es: $user->email.<br>" .
                    "Por favor, inicia sesión con tu email y contraseña para comenzar.<br><br>" .
                    "Saludos,<br>El equipo de " . APP_NAME
            );
            $email->send();
            Session::success("Usuario " . ($user->displayname ?? 'sin nombre') . " registrado correctamente. Inicia sesión para continuar.");
            return redirect('/login');
        } catch (ValidationException $e) {
            Session::error("Errores de validación: " . $e->getMessage());
            return redirect("/user/register");
        } catch (SQLException $e) {
            Session::error("Se produjo un error al registrar el usuario " . ($user->displayname ?? 'sin nombre') . ".");
            if (DEBUG) {
                throw new SQLException($e->getMessage());
            }
            return redirect("/user/register");
        } catch (EmailException $e) {
            Session::warning("Usuario " . ($user->displayname ?? 'sin nombre') . " registrado correctamente, pero no se pudo enviar el email de bienvenida.");
            return redirect('/login');
        } catch (Exception $e) {
            Session::warning("Usuario " . ($user->displayname ?? 'sin nombre') . " registrado correctamente, pero no se pudo subir la imagen de perfil.");
            if (DEBUG) {
                throw new UploadException($e->getMessage());
            }
            return redirect("/login");
        }
    }

    public function create()
    {
        Auth::admin();
        return view('user/create');
    }

    public function store()
    {
        Auth::admin();
        if (!request()->has('guardar')) {
            throw new FormException('No se recibió el formulario');
        }
        $user = new User();
        $user->displayname = request()->post('displayname');
        $user->email = request()->post('email');
        $user->phone = request()->post('phone');
        $user->password = request()->post('password');

        $user->roles = [];
        $user->addRole('ROLE_USER');
        $rolesFromForm = request()->post('roles');
        if ($rolesFromForm && $rolesFromForm !== 'ROLE_USER') {
            $user->addRole($rolesFromForm);
        }

        try {
            if ($errores = $user->validate(false, false, USER_ROLES)) {
                throw new ValidationException(
                    "<br>" . arrayToString($errores, false, false, ".<br>")
                );
            }

            $user->password = md5($user->password); // Usamos MD5 en lugar de BCRYPT
            $user->saneate();
            $user->save();

            $file = request()->file('picture', 8000000, ['image/png', 'image/jpeg', 'image/gif', 'image/webp']);
            if ($file) {
                $user->picture = $file->store('../public/' . USER_IMAGE_FOLDER, 'user_');
                $user->update();
            }

            $email = new Email(
                $user->email,
                ADMIN_EMAIL,
                APP_NAME,
                'Bienvenido a ' . APP_NAME,
                "Hola $user->displayname,<br><br>" .
                    "Tu cuenta ha sido creada exitosamente en " . APP_NAME . ".<br>" .
                    "Tu email registrado es: $user->email.<br>" .
                    "Por favor, inicia sesión con tu email y contraseña para comenzar.<br><br>" .
                    "Saludos,<br>El equipo de " . APP_NAME
            );
            $email->send();
            Session::success("Usuario " . ($user->displayname ?? 'sin nombre') . " creado correctamente. Se ha enviado un email de bienvenida.");
            return redirect('/user/list');
        } catch (ValidationException $e) {
            Session::error("Errores de validación: " . $e->getMessage());
            return redirect("/user/create");
        } catch (SQLException $e) {
            Session::error("Se produjo un error al guardar el usuario " . ($user->displayname ?? 'sin nombre') . ".");
            if (DEBUG) {
                throw new SQLException($e->getMessage());
            }
            return redirect("/user/create");
        } catch (EmailException $e) {
            Session::warning("Usuario " . ($user->displayname ?? 'sin nombre') . " creado correctamente, pero no se pudo enviar el email de bienvenida.");
            return redirect('/user/list');
        } catch (Exception $e) {
            Session::warning("Usuario " . ($user->displayname ?? 'sin nombre') . " creado correctamente, pero no se pudo subir la imagen de perfil.");
            if (DEBUG) {
                throw new UploadException($e->getMessage());
            }
            return redirect("/user/list");
        }
    }

    public function list(int $page = 1)
    {
        Auth::admin();
        $filtro = Filter::apply('usuarios');
        $limit = RESULTS_PER_PAGE;
        if ($filtro) {
            $total = User::filteredResults($filtro);
            $paginator = new Paginator('/user/list', $page, $limit, $total);
            $users = User::filter($filtro, $limit, $paginator->getOffset());
        } else {
            $total = User::total();
            $paginator = new Paginator('/user/list', $page, $limit, $total);
            $users = User::orderBy('displayname', 'ASC', $limit, $paginator->getOffset()); // Cambia 'displayname' a 'displayname'
        }
        return view('user/list', [
            'users' => $users,
            'paginator' => $paginator,
            'filtro' => $filtro
        ]);
    }

    public function show(int $id = 0)
    {
        Auth::admin();
        $user = User::findOrFail($id, "No se encontró el usuario con el ID indicado");
        return view('user/show', ['user' => $user]);
    }

    public function edit(int $id = 0)
    {
        Auth::admin();
        if (!$id) {
            throw new NothingToFindException('No se indicó el usuario a editar');
        }
        $user = User::findOrFail($id, "No se encontró el usuario con el ID indicado");
        $availableRoles = $user->getAvailableRoles();
        return view('user/edit', [
            'user' => $user,
            'availableRoles' => $availableRoles,
            'isAdminEdit' => true // Indicamos que es edición de admin
        ]);
    }

    public function editProfile()
    {
        Auth::check();
        $user = Login::user();
        return view('user/edit', [
            'user' => $user,
            'isAdminEdit' => false // Indicamos que es edición de perfil
        ]);
    }

    public function updateProfile()
    {
        Auth::check();
        if (!request()->has('actualizar')) {
            throw new FormException('No se recibieron datos');
        }
        $user = Login::user();
        $id = intval(request()->post('id'));

        if ($user->id !== $id) {
            Session::error("No tienes permiso para editar este perfil.");
            return redirect("/user/home");
        }

        $user->displayname = request()->post('displayname');
        $user->email = request()->post('email');
        $user->phone = request()->post('phone');
        $user->poblacion = request()->post('poblacion');
        $user->cp = request()->post('cp');

        try {
            if ($errores = $user->validate(true, true, USER_ROLES)) {
                throw new ValidationException(
                    "<br>" . arrayToString($errores, false, false, ".<br>")
                );
            }

            $user->saneate();
            $user->update();

            $file = request()->file('picture', 8000000, ['image/png', 'image/jpeg', 'image/gif', 'image/webp']);
            if ($file) {
                if ($user->picture) {
                    File::remove('../public/' . USER_IMAGE_FOLDER . '/' . $user->picture);
                }
                $user->picture = $file->store('../public/' . USER_IMAGE_FOLDER, 'user_');
                $user->update();
            }

            Session::success("Perfil actualizado correctamente.");
            return redirect("/user/home");
        } catch (ValidationException $e) {
            Session::error("Errores de validación: " . $e->getMessage());
            return redirect("/user/editprofile");
        } catch (SQLException $e) {
            Session::error("No se pudo actualizar el perfil.");
            if (DEBUG) {
                throw new SQLException($e->getMessage());
            }
            return redirect("/user/editprofile");
        } catch (UploadException $e) {
            Session::warning("Perfil actualizado, pero no se modificó la foto.");
            if (DEBUG) {
                throw new UploadException($e->getMessage());
            }
            return redirect("/user/home");
        }
    }

    public function update()
    {
        Auth::admin();
        if (!request()->has('actualizar')) {
            throw new FormException('No se recibieron datos');
        }
        $id = intval(request()->post('id'));
        $user = User::findOrFail($id, "No se ha encontrado el usuario.");
        $user->displayname = request()->post('displayname');
        $user->email = request()->post('email');
        $user->phone = request()->post('phone');
        $user->poblacion = request()->post('poblacion');
        $user->cp = request()->post('cp');
        $password = $_POST['password'] ?? '';
        if (!empty($password)) {
            $user->password = $password;
        }

        try {
            if ($errores = $user->validate(true, true, USER_ROLES)) {
                throw new ValidationException(
                    "<br>" . arrayToString($errores, false, false, ".<br>")
                );
            }

            if (!empty($password)) {
                $user->password = md5($user->password); // Usamos MD5
            }
            $user->saneate();
            $user->update();

            $file = request()->file('picture', 8000000, ['image/png', 'image/jpeg', 'image/gif', 'image/webp']);
            if ($file) {
                if ($user->picture) {
                    File::remove('../public/' . USER_IMAGE_FOLDER . '/' . $user->picture);
                }
                $user->picture = $file->store('../public/' . USER_IMAGE_FOLDER, 'user_');
                $user->update();
            }
            Session::success("Actualización del usuario $user->displayname correcta.");
            return redirect("/user/edit/$id");
        } catch (ValidationException $e) {
            Session::error("Errores de validación: " . $e->getMessage());
            return redirect("/user/edit/$id");
        } catch (SQLException $e) {
            Session::error("No se pudo actualizar el usuario $user->displayname.");
            if (DEBUG) {
                throw new SQLException($e->getMessage());
            }
            return redirect("/user/edit/$id");
        } catch (UploadException $e) {
            Session::warning("Cambios guardados, pero no se modificó la foto.");
            if (DEBUG) {
                throw new UploadException($e->getMessage());
            }
            return redirect("/user/edit/$id");
        }
    }

    public function addRole()
    {
        Auth::admin();
        if (!request()->has('add')) {
            throw new FormException("No se recibió el formulario.");
        }

        $iduser = intval(request()->post('iduser'));
        $role = request()->post('role');
        $user = User::findOrFail($iduser, "No se encontró el usuario");

        try {
            $user->addRole($role);
            $user->update();
            Session::success("Se ha añadido el rol $role a $user->displayname.");
            return redirect("/user/edit/$iduser");
        } catch (SQLException $e) {
            Session::error("No se pudo añadir el rol $role a $user->displayname.");
            if (DEBUG) throw new SQLException($e->getMessage());
            return redirect("/user/edit/$iduser");
        }
    }

    public function removeRole()
    {
        Auth::admin();
        if (!request()->has('remove')) {
            throw new FormException("No se recibió el formulario.");
        }

        $iduser = intval(request()->post('iduser'));
        $role = request()->post('role');
        $user = User::findOrFail($iduser, "No se encontró el usuario");

        try {
            $user->removeRole($role);
            $user->update();
            Session::success("Se ha quitado el rol $role de $user->displayname.");
            return redirect("/user/edit/$iduser");
        } catch (SQLException $e) {
            Session::error("No se pudo quitar el rol $role de $user->displayname.");
            if (DEBUG) throw new SQLException($e->getMessage());
            return redirect("/user/edit/$iduser");
        }
    }

    public function droppicture()
    {
        Auth::admin();
        if (!request()->has('borrar')) {
            throw new FormException('Faltan datos para completar la operación');
        }
        $id = request()->post('id');
        $user = User::findOrFail($id, "No se ha encontrado el usuario.");
        $tmp = $user->picture;
        $user->picture = null;
        try {
            $user->update();
            if ($tmp) {
                File::remove('../public/' . USER_IMAGE_FOLDER . '/' . $tmp, true);
            }
            Session::success("Borrado de la foto de $user->displayname realizado.");
            return redirect("/user/edit/$id");
        } catch (SQLException $e) {
            Session::error("No se pudo eliminar la foto");
            if (DEBUG) {
                throw new SQLException($e->getMessage());
            }
            return redirect("/user/edit/$id");
        } catch (FileException $e) {
            Session::warning("No se pudo eliminar el fichero del disco.");
            if (DEBUG) {
                throw new FileException($e->getMessage());
            }
            return redirect("/user/edit/$id");
        }
    }

    public function changepassword()
    {
        Auth::check();
        if (!request()->has('cambiar')) {
            throw new FormException('No se recibió el formulario');
        }
        $user = Login::user();
        $currentPassword = $_POST['currentpassword'];
        if (md5($currentPassword) !== $user->password) { // Verificamos con MD5
            Session::error("La contraseña actual no es correcta.");
            return redirect("/user/home");
        }
        $newPassword = $_POST['newpassword'];
        $repeatPassword = $_POST['repeatpassword'];
        if ($newPassword !== $repeatPassword) {
            Session::error("Las nuevas contraseñas no coinciden.");
            return redirect("/user/home");
        }
        $user->password = md5($newPassword); // Solo MD5, sin BCRYPT

        try {
            if ($errores = $user->validate(false, true, USER_ROLES)) {
                throw new ValidationException(
                    "<br>" . arrayToString($errores, false, false, ".<br>")
                );
            }
            $user->update();
            Session::success("Contraseña cambiada correctamente.");
            return redirect("/user/home");
        } catch (ValidationException $e) {
            Session::error("Errores de validación: " . $e->getMessage());
            return redirect("/user/home");
        } catch (SQLException $e) {
            Session::error("No se pudo cambiar la contraseña.");
            if (DEBUG) {
                throw new SQLException($e->getMessage());
            }
            return redirect("/user/home");
        }
    }

    public function delete(int $id = 0)
    {
        Auth::admin();
        $user = User::findOrFail($id, "No existe el usuario.");
        return view('user/delete', ['user' => $user]);
    }

    public function destroy()
    {
        Auth::admin();
        if (!request()->has('borrar')) {
            throw new FormException('No se recibió la confirmación');
        }
        $id = intval(request()->post('id'));
        $user = User::findOrFail($id, "No se ha encontrado el usuario.");

        if ($user->id == Login::user()->id) {
            Session::error("No puedes eliminar tu propia cuenta.");
            return redirect("/user/delete/$id");
        }
        if (in_array('ROLE_ADMIN', $user->roles)) {
            Session::error("No se puede eliminar a un administrador.");
            return redirect("/user/delete/$id");
        }

        try {
            if ($user->picture) {
                File::remove('../public/' . USER_IMAGE_FOLDER . '/' . $user->picture, true);
            }
            $user->deleteObject();
            Session::success("Se ha borrado el usuario $user->displayname.");
            return redirect('/user/list');
        } catch (SQLException $e) {
            Session::error("No se pudo borrar el usuario $user->displayname.");
            if (DEBUG) {
                throw new SQLException($e->getMessage());
            }
            return redirect("/user/delete/$id");
        } catch (FileException $e) {
            Session::warning("Se eliminó el usuario $user->displayname, pero no se pudo eliminar el fichero del disco.");
            if (DEBUG) {
                throw new FileException($e->getMessage());
            }
            return redirect("/user/list");
        }
    }

    // Nueva acción para bloquear usuarios
    public function block(int $id = 0)
    {
        Auth::admin();
        $user = User::findOrFail($id, "No existe el usuario.");
        if ($user->id == Login::user()->id) {
            Session::error("No puedes bloquear tu propia cuenta.");
            return redirect("/user/list");
        }
        if (in_array('ROLE_ADMIN', $user->roles)) {
            Session::error("No se puede bloquear a un administrador.");
            return redirect("/user/list");
        }

        try {
            $user->blocked_at = date('Y-m-d H:i:s');
            if (!in_array('ROLE_BLOCKED', $user->roles)) {
                $user->roles[] = 'ROLE_BLOCKED';
            }
            $user->update();
            Session::success("El usuario $user->displayname ha sido bloqueado.");
            return redirect('/user/list');
        } catch (SQLException $e) {
            Session::error("No se pudo bloquear el usuario $user->displayname.");
            if (DEBUG) {
                throw new SQLException($e->getMessage());
            }
            return redirect("/user/list");
        }
    }

    // Nueva acción para desbloquear usuarios
    public function unblock(int $id = 0)
    {
        Auth::admin();
        $user = User::findOrFail($id, "No existe el usuario.");

        try {
            $user->blocked_at = null;
            $user->update();
            Session::success("El usuario $user->displayname ha sido desbloqueado.");
            return redirect('/user/list');
        } catch (SQLException $e) {
            Session::error("No se pudo desbloquear el usuario $user->displayname.");
            if (DEBUG) {
                throw new SQLException($e->getMessage());
            }
            return redirect("/user/list");
        }
    }

    public function checkemail(string $email = ''): JsonResponse
    {
        if (!Auth::check() || !Login::user()->hasRole('ROLE_ADMIN')) {
            return new JsonResponse(
                [],
                'Operación no autorizada',
                401,
                'NOT AUTHORIZED'
            );
        }
        $user = User::whereExactMatch(['email' => $email])[0] ?? null;
        return new JsonResponse(['found' => $user ? true : false]);
    }
}