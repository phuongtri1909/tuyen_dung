<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Department::query();
        
        // Xử lý tìm kiếm
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }
        
        $departments = $query->latest()->paginate(10);
        
        return view('admin.pages.departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name'
        ], [
            'name.required' => 'Tên phòng ban không được để trống',
            'name.unique' => 'Tên phòng ban đã tồn tại',
            'name.max' => 'Tên phòng ban không quá 255 ký tự'
        ]);
        
        Department::create($validated);
        
        return redirect()->route('departments.index')
            ->with('success', 'Thêm phòng ban thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        return view('admin.pages.departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        return view('admin.pages.departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id
        ], [
            'name.required' => 'Tên phòng ban không được để trống',
            'name.unique' => 'Tên phòng ban đã tồn tại',
            'name.max' => 'Tên phòng ban không quá 255 ký tự'
        ]);
        
        $department->update($validated);
        
        return redirect()->route('departments.index')
            ->with('success', 'Cập nhật phòng ban thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        try {
            $department->delete();
            return redirect()->route('departments.index')
                ->with('success', 'Xóa phòng ban thành công!');
        } catch (\Exception $e) {
            return redirect()->route('departments.index')
                ->with('error', 'Không thể xóa phòng ban này!');
        }
    }
}