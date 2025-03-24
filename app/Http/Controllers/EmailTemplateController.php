<?php

namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    /**
     * Hiển thị trang quản lý mẫu email
     */
    public function index()
    {
        $templates = EmailTemplate::all();
        return view('admin.pages.emails.index', compact('templates'));
    }

    /**
     * Hiển thị form chỉnh sửa mẫu email
     */
    public function edit($id)
    {
        $template = EmailTemplate::findOrFail($id);
        return view('admin.pages.emails.edit', compact('template'));
    }

    /**
     * Cập nhật mẫu email
     */
    public function update(Request $request, $id)
    {
        $template = EmailTemplate::findOrFail($id);

        $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required'
        ]);

        $template->update([
            'subject' => $request->subject,
            'content' => $request->content
        ]);

        return redirect()->route('email-templates.index')
            ->with('success', 'Đã cập nhật mẫu email thành công');
    }
}
