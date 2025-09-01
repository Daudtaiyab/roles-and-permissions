<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:permissions.view')->only(['index', 'show']);
        $this->middleware('permission:permissions.create')->only(['create', 'store']);
        $this->middleware('permission:permissions.edit')->only(['edit', 'update']);
        $this->middleware('permission:permissions.delete')->only(['destroy']);
    }
    public function index()
    {
        $permissions = Permission::all();
        return view('permissions.list', compact('permissions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions|min:3',
        ]);

        if ($validator->passes()) {
            Permission::create(['name' => $request->name]);
            return redirect()->route('permissions.list')->with('success', 'Permission added successfully.');
        } else {
            return redirect()->route('permissions.create')->withInput()->withErrors($validator);
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
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('permissions/edit', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // dd($id);
        $permission = Permission::findOrFail($id);
        // dd($permission);
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:permissions,name,' . $id . ',id|min:3',
        ]);
        if ($validator->passes()) {
            // Permission::create(['name' => $request->name]);
            $permission->name = $request->name;
            $permission->save();
            return redirect()->route('permissions.list')->with('success', 'Permission updated successfully.');
        } else {
            return redirect()->route('permissions.edit', $id)->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $permission = Permission::findOrFail($request->id);
        if ($permission->delete()) {
            return response()->json(['message' => 'Permission deleted successfully.', 'status' => true]);
        } else {
            return response()->json(['message' => 'Oops something went wrong!', 'status' => false]);
        }
    }
}