<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserEditRequest;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index', [
            'users' => User::all()
        ]);
    }

    public function create()
    {
        return view('users.create', [
            'units' => Unit::orderBy('id')->get()
        ]);
    }

    public function store(UserCreateRequest $request)
    {
        $validateData = $request->validated();
        $validateData['password'] = Hash::make($validateData['password']);
        User::create($validateData);
        return redirect()->route('users')->withSuccess('Data User Berhasil Ditambahkan');
    }

    public function edit(User $user)
    {
        return view('users.edit', [
            'user' => $user,
            'units' => Unit::orderBy('id')->get()
        ]);
    }

    public function update(UserEditRequest $request, User $user)
    {
        $validateData = $request->validated();
        $validateData['password'] = Hash::make($validateData['password']);
        $user->update($validateData);
        return redirect()->route('users')->withSuccess('Data User Berhasil Diubah');
    }

    public function delete(User $user)
    {
        $user->delete();
        return redirect()->route('users')->withSuccess('Data User Berhasil Dihapus');
    }

    public function changeRole(User $user, $role)
    {
        if ($role == 1) {
            $user->role = 2;
        } else {
            $user->role = 1;
        }
        $user->save();
        return redirect()->route('users')->withSuccess('Role User Berhasil Diubah');
    }

    public function loginView()
    {
        if (Auth::viaRemember() || Auth::check()) {
            return route('dashboard');
        }
        return view('auth.login');
    }

    public function changePasswordView()
    {
        return view('auth.change-password');
    }

    public function changePassword(ChangePasswordRequest $request, User $user)
    {
        $validateData = $request->validated();
        $user->password = Hash::make($validateData['password']);
        $user->save();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return back()->withSuccess('Berhasil Mengubah Password Silahkan Login Kembali');
    }

    public function login(LoginRequest $request)
    {
        $validateData = $request->validated();
        $remember = $request->has('remember') ? true : false;

        if (Auth::attempt(['username' => $validateData['username'], 'password' => $validateData['password']], $remember)) {
            $request->session()->regenerate();
            User::where('username', $validateData['username'])->update(['last_login' => Carbon::now()->toDateTimeString()]);
            return redirect('/')->withSuccess('Selamat Datang ' . Auth::user()->name);
        } else {
            return redirect('/login')->with('errors', 'Username atau Password Salah');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login')->withSuccess('Berhasil Logout');
    }
}