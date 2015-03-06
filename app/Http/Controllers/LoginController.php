<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use Collective\Annotations\Routing\Annotations\Annotations\Middleware;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Request;

/**
 * Class LoginController
 * @package App\Http\Controllers
 */
class LoginController extends Controller {

    /**
     * @var Guard
     */
    private $auth;
    /**
     * @var User
     */
    private $user;

    public function __construct(Guard $auth, User $user)
    {
        $this->auth = $auth;
        $this->user = $user;
    }

    /**
     * @Get("login")
     * @Middleware("guest")
     * @return mixed
     */
	public function showLogin()
    {
        return view()->make('auth.login');
    }

    /**
     * @Post("login")
     * @Middelware("guest")
     * @param Request $request
     */
    public function doLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($this->auth->attempt($request->only('email', 'password'), $request->remember)) {
            return redirect('/');
        }

        return redirect()->back()->withErrors([
            'email' => 'Something went wrong'
        ]);
    }

    /**
     * @Get("logout")
     * @Middleware("auth")
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function doLogout()
    {
        $this->auth->logout();
        return redirect('/login');
    }

    /**
     * @Get("register")
     * @return mixed
     */
    public function showRegister()
    {
        return view()->make('auth.register');
    }

    /**
     * @Post("register")
     * @param Request $request
     */
    public function doRegister(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed'
        ]);

        $user = $this->user->create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $this->auth->login($user);
        return redirect('/home');
    }

    /**
     * @Get("home")
     * @Middleware("auth")
     * @return mixed
     */
    public function showHome()
    {
        return view()->make('home');
    }

}
