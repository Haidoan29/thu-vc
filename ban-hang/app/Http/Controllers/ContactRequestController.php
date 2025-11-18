<?php

namespace App\Http\Controllers;

use App\Models\ContactRequest;
use Illuminate\Http\Request;

class ContactRequestController extends Controller
{
    // Hiển thị danh sách tất cả contact requests
    public function index()
    {
        $contacts = ContactRequest::latest()->paginate(10);
        return view('contact_requests.index', compact('contacts'));
    }

    // Hiển thị form tạo contact request mới
    public function create()
    {
        return view('contact_requests.create');
    }

    // Lưu contact request mới
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'message' => 'required|string',
        ]);

        ContactRequest::create($request->all());

        return redirect('/lien-he')
            ->with('success', 'Gửi yêu cầu thành công!');
    }

    // Hiển thị chi tiết contact request
    public function show($id)
    {
        $contact = ContactRequest::findOrFail($id);
        return view('contact_requests.show', compact('contact'));
    }

    // Hiển thị form chỉnh sửa
    public function edit($id)
    {
        $contact = ContactRequest::findOrFail($id);
        return view('contact_requests.edit', compact('contact'));
    }

    // Cập nhật contact request
    public function update(Request $request, $id)
    {
        $contact = ContactRequest::findOrFail($id);

        $request->validate([
            'full_name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:100',
            'message' => 'required|string',
        ]);

        $contact->update($request->all());

        return redirect()->route('contact_requests.index')
            ->with('success', 'Cập nhật thành công!');
    }

    // Xóa contact request
    public function destroy($id)
    {
        $contact = ContactRequest::findOrFail($id);
        $contact->delete();

        return redirect()->route('contact_requests.index')
            ->with('success', 'Xóa thành công!');
    }
}
