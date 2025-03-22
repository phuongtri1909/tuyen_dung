<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Xử lý tìm kiếm
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }
        
        // Phân quyền: nếu không phải admin thì chỉ xem được user cùng phòng ban
        if (auth()->user()->role != 'admin') {
            $query->where('department_id', auth()->user()->department_id);
        }
        
        $users = $query->latest()->paginate(10);
        
        return view('admin.pages.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Kiểm tra quyền, chỉ admin mới được tạo user
        if (auth()->user()->role != 'admin') {
            return redirect()->route('users.index')
                ->with('error', 'Bạn không có quyền thực hiện chức năng này!');
        }
        
        $departments = Department::all();
        return view('admin.pages.users.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Kiểm tra quyền, chỉ admin mới được tạo user
        if (auth()->user()->role != 'admin') {
            return redirect()->route('users.index')
                ->with('error', 'Bạn không có quyền thực hiện chức năng này!');
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:admin,hr,lm,final',
            'department_id' => 'required|exists:departments,id',
        ], [
            'name.required' => 'Tên không được để trống',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'role.required' => 'Vai trò không được để trống',
            'role.in' => 'Vai trò không hợp lệ',
            'department_id.required' => 'Phòng ban không được để trống',
            'department_id.exists' => 'Phòng ban không tồn tại',
        ]);
        
        // Hash mật khẩu
        $validated['password'] = Hash::make($validated['password']);
        
        User::create($validated);
        
        return redirect()->route('users.index')
            ->with('success', 'Thêm nhân viên thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Nếu không phải admin và không phải chính user đó
        if (auth()->user()->role != 'admin' && auth()->id() != $user->id) {
            return redirect()->route('users.index')
                ->with('error', 'Bạn không có quyền xem thông tin này!');
        }
        
        return view('admin.pages.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Nếu không phải admin và không phải chính user đó
        if (auth()->user()->role != 'admin' && auth()->id() != $user->id) {
            return redirect()->route('users.index')
                ->with('error', 'Bạn không có quyền chỉnh sửa người dùng này!');
        }
        
        $departments = Department::all();
        return view('admin.pages.users.edit', compact('user', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Nếu không phải admin và không phải chính user đó
        if (auth()->user()->role != 'admin' && auth()->id() != $user->id) {
            return redirect()->route('users.index')
                ->with('error', 'Bạn không có quyền chỉnh sửa người dùng này!');
        }
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'department_id' => 'required|exists:departments,id',
        ];
        
        // Nếu là admin mới được sửa role
        if (auth()->user()->role == 'admin') {
            $rules['role'] = 'required|string|in:admin,hr,lm,final';
        }
        
        // Mật khẩu là optional khi update
        if ($request->filled('password')) {
            $rules['password'] = 'string|min:6';
        }
        
        $validated = $request->validate($rules, [
            'name.required' => 'Tên không được để trống',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'role.required' => 'Vai trò không được để trống',
            'role.in' => 'Vai trò không hợp lệ',
            'department_id.required' => 'Phòng ban không được để trống',
            'department_id.exists' => 'Phòng ban không tồn tại',
        ]);
        
        // Xử lý mật khẩu nếu được cung cấp
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        // Nếu không phải admin, không cho phép sửa role
        if (auth()->user()->role != 'admin') {
            unset($validated['role']);
        }
        
        $user->update($validated);
        
        return redirect()->route('users.index')
            ->with('success', 'Cập nhật nhân viên thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Chỉ admin mới được xóa user và không thể tự xóa mình
        if (auth()->user()->role != 'admin') {
            return redirect()->route('users.index')
                ->with('error', 'Bạn không có quyền xóa người dùng!');
        }
        
        if (auth()->id() == $user->id) {
            return redirect()->route('users.index')
                ->with('error', 'Bạn không thể tự xóa tài khoản của mình!');
        }
        
        try {
            $user->delete();
            return redirect()->route('users.index')
                ->with('success', 'Xóa nhân viên thành công!');
        } catch (\Exception $e) {
            return redirect()->route('users.index')
                ->with('error', 'Không thể xóa nhân viên này!');
        }
    }
}