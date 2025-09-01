<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:roles.view')->only(['index', 'show']);
        $this->middleware('permission:roles.create')->only(['create', 'store']);
        $this->middleware('permission:roles.edit')->only(['edit', 'update']);
        $this->middleware('permission:roles.delete')->only(['destroy']);
    }
    public function index()
    {
        $roles = Role::all();
        return view("roles.list", compact("roles"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permission = Permission::orderBy("name", "asc")->get();
        return view("roles.create", compact("permission"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles|min:3',
        ]);

        if ($validator->passes()) {
            $role = Role::create(['name' => $request->name]);
            if (! empty($request->permission)) {
                foreach ($request->permission as $name) {
                    $role->givePermissionTo($name);
                }
            }
            return redirect()->route('roles.list')->with('success', 'Role added successfully.');
        } else {
            return redirect()->route('roles.create')->withInput()->withErrors($validator);
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
        $roles          = Role::findOrFail($id);
        $permission     = Permission::orderBy('name', 'asc')->get();
        $hasPermissions = $roles->permissions->pluck('name');
        // dd($permissions);
        return view('roles/edit', compact('roles', 'permission', 'hasPermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role      = Role::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:roles,name,' . $id . ',id',
        ]);

        if ($validator->passes()) {
            // $role = Role::create(['name' => $request->name]);
            $role->name = $request->name;
            $role->save();
            if (! empty($request->permission)) {
                $role->syncPermissions($request->permission);
            } else {
                $role->syncPermissions([]);
            }
            return redirect()->route('roles.list')->with('success', 'Role updated successfully.');
        } else {
            return redirect()->route('roles.create', $id)->withInput()->withErrors($validator);
        }
    }

/**
 * Remove the specified resource from storage.
 */
    public function destroy(Request $request)
    {
        $role = Role::findOrFail($request->id);
        if ($role->delete()) {
            return response()->json(['message' => 'Role deleted successfully.', 'status' => true]);
        } else {
            return response()->json(['message' => 'Oops something went wrong!', 'status' => false]);
        }
    }
}