<?php

namespace App\Http\Controllers;

use App\Models\User;
use Backpack\CRUD\app\Library\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Backpack\CRUD\app\Http\Controllers\Auth\RegisterController as BackpackRegisterController;
use Illuminate\Support\Facades\Log;

class CustomRegisterController extends BackpackRegisterController
{

    use RegistersUsers;


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return Validator
     */
    protected function validator(array $data)
    {
        $user_model_fqn = config('backpack.base.user_model_fqn');
        $user = new $user_model_fqn();
        $users_table = $user->getTable();
        $email_validation = backpack_authentication_column() == 'email' ? 'email|' : '';

        return Validator::make($data, [
            'name'                             => 'required|max:255',
            backpack_authentication_column()   => 'required|'.$email_validation.'max:255|unique:'.$users_table,
            'password'                         => 'required|min:6|confirmed',
        ]);
    }


    public function showRegistrationForm()
    {
        // if registration is closed, deny access
        if (! config('backpack.base.registration_open')) {
            abort(403, trans('backpack::base.registration_closed'));
        }

        $this->data['title'] = trans('backpack::base.registration_closed'); // set the page title

        return view(backpack_view('auth.register'), $this->data);
    }

//     public function register(Request $request)
//     {
//         // if registration is closed, deny access
//         if (! config('backpack.base.registration_open')) {
//             abort(403, trans('backpack::base.registration_closed'));
//         }

//         Log::info($request->all());

// //        $this->validator($request->all())->validate();

//         $user = $this->create($request->all());

//         if($user){
//             event(new Registered($user));
//             //$this->guard()->login($user);
//             return redirect($this->redirectPath());
//         }else{
//             echo "Fallo";
//         }
//     }

    public function register(Request $request)
    {
        try {
            // if registration is closed, deny access
            if (!config('backpack.base.registration_open')) {
                abort(403, trans('backpack::base.registration_closed'));
            }

            Log::info($request->all());

            // Validar los datos del formulario
            // $this->validator($request->all())->validate();

            // Crear el usuario
            $user = $this->create($request->all());

            if ($user) {
                event(new Registered($user));

                // Autologin después del registro exitoso
                $this->guard()->login($user);

                return redirect($this->redirectPath() . '/dashboard');
            } else {
                // Enviar mensaje de fallo
                echo "No se pudo realizasr el registro";
            }
        } catch (\Exception $e) {
            // Manejar la excepción y mostrar un mensaje de error
            echo "Error: " . $e->getMessage();
        }
    }

    protected function create(array $data)
    {
        try{

            $user = new User;

            $user->name = $data['name'];
            $user->email = $data[backpack_authentication_column()];
            $user->password = bcrypt($data['password']);
            $user->password_confirmation = bcrypt($data['password']);
            $user->nif = $data['nif'];
            $user->nationality = $data['nationality'];
            $user->documento_identidad = $data['documento_identidad'];

            $user->save();

            return $user;
        }catch(\Exception $e){
            Log::error($e->getMessage());
            return redirect()->back();
        }
    }

    protected function guard()
    {
        return backpack_auth();
    }

}
