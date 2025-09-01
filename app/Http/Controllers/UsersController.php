<?php
namespace App\Http\Controllers;

use App\Models\User;
use function Laravel\Prompts\password;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:users.view')->only(['index', 'show']);
        $this->middleware('permission:users.create')->only(['create', 'store']);
        $this->middleware('permission:users.edit')->only(['edit', 'update']);
        $this->middleware('permission:users.delete')->only(['destroy']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view("users/list", compact("users"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view("users/create", compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            "name"     => "required|min:3",
            "email"    => "required|email",
            'password' => [
                'required',
                Password::min(8)
                    ->mixedCase() // Requires at least one uppercase and one lowercase letter
                    ->letters()   // Requires at least one letter
                    ->numbers()   // Requires at least one number
                    ->symbols(),  // Checks if the password has appeared in public data leaks
            ],
        ]);
        if ($validator->passes()) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => $request->password,
            ]);
            $user->assignRole($request->role);
            return redirect()->route('users.list')->with('success', 'User created successfully');
        } else {
            return redirect()->route('users.create')->withErrors($validator)->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $users    = User::findOrFail($id);
        $roles    = Role::all();
        $hasRoles = $users->roles->pluck('id');
        return view('users/edit', compact('users', 'roles', 'hasRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $users = User::findOrFail($id);
        // dd($id);
        $validator = Validator::make($request->all(), [
            "name"  => [
                "required",
                "min:10",
                Rule::unique("users", "name")->ignore($id),
            ],
            "email" => "required|email",
        ]);
        if ($validator->passes()) {
            $users->name  = $request->name;
            $users->email = $request->email;
            $users->syncRoles($request->role);
            $users->save();
            return redirect()->route("users.list")->with("success", "User updated successfully");
        } else {
            return redirect()->route("users.edit", $request->id)->withErrors($validator)->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        // $user = User::findOrFail($request->id);

        // if ($user->delete()) {
        //     return response()->json(['message' => 'User deleted successfully.', 'status' => true]);
        // }

        // return response()->json(['message' => 'Oops something went wrong!', 'status' => false]);
    }
}