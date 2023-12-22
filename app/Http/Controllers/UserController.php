<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller{
    public function index(){
        $users = User::all();
        return view('user.index', compact('users'));
    }

    public function create()
    {
        //menampilkan layouting html pada folder resources-views
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'role' => 'required',
            'email' => 'required|min:3',
            // 'password' => 'required',
        ]);

        $password = substr($request->email, 0, 3) . substr($request->name, 0, 3);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $password,
            'role' => $request->role,
        ]);

        // atau jika seluruh data input akan dimasukkan langsung ke db bisa dengan perintah Medicine::create($request->all());
        return redirect()->back()->with('success', 'Berhasil Menambahkan !');
    }


    public function edit($id)
    {
        $users = User::find($id);

        return view('user.edit', compact('users'));
    }


    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('user.home')->with('error', 'Akun tidak ditemukan.');
        }

        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,cashier',
        ]);

        if ($request->password) {
            // $password = substr($request->email, 0, 3).substr($request->name, 0, 3);
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'password' => Hash::make($request->password),
            ]);
        } else {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ]);
        }

        return redirect()->route('user.index')->with('success', 'Akun berhasil diperbarui.');
    }

    public function destroy($id)
    {
        User::where('id', $id)->delete();

        return redirect()->back()->with('deleted', 'Berhasil Menghapus data!');
    }

    public function loginAuth(Request $request)
    {
        $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);

        $user = $request->only(['email','password']);

        if (Auth::attempt($user)) {
            return redirect()->route('home.page');
        }else{
            return redirect()->back()->with('failed', 'Proses login gagal, silahkan coba kembali dengan data yang benar!');
        }
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login')->with('logout','anda telah logout!!');
    }

}
