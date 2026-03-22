<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(20);
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $messages = app()->getLocale() == 'ar' ? [
            'name.required' => 'حقل الاسم مطلوب.',
            'name.max' => 'الاسم طويل جداً.',
            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.email' => 'البريد الإلكتروني غير صالح.',
            'email.unique' => 'البريد الإلكتروني مستخدم مسبقاً من قبل مستخدم آخر.',
            'password.required' => 'حقل كلمة المرور مطلوب.',
            'password.min' => 'يجب أن تتكون كلمة المرور من 8 أحرف على الأقل.',
            'password.symbols' => 'يجب أن تحتوي كلمة المرور على رمز واحد على الأقل.',
            'password.numbers' => 'يجب أن تحتوي كلمة المرور على رقم واحد على الأقل.',
            'password.letters' => 'يجب أن تحتوي كلمة المرور على حرف واحد على الأقل.',
        ] : [];

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'string', Password::min(8)->letters()->numbers()->symbols()],
        ], $messages);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'User created successfully.');
    }

    public function update(Request $request, User $user)
    {
        $messages = app()->getLocale() == 'ar' ? [
            'name.required' => 'حقل الاسم مطلوب.',
            'name.max' => 'الاسم طويل جداً.',
            'email.required' => 'حقل البريد الإلكتروني مطلوب.',
            'email.email' => 'البريد الإلكتروني غير صالح.',
            'email.unique' => 'البريد الإلكتروني مستخدم مسبقاً من قبل مستخدم آخر.',
            'password.min' => 'يجب أن تتكون كلمة المرور من 8 أحرف على الأقل.',
            'password.symbols' => 'يجب أن تحتوي كلمة المرور على رمز واحد على الأقل.',
            'password.numbers' => 'يجب أن تحتوي كلمة المرور على رقم واحد على الأقل.',
            'password.letters' => 'يجب أن تحتوي كلمة المرور على حرف واحد على الأقل.',
        ] : [];

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', Password::min(8)->letters()->numbers()->symbols()],
        ], $messages);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return back()->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['message' => 'You cannot delete yourself.']);
        }
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }
}
