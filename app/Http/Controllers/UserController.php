<?php
namespace App\Http\Controllers;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Hash;
use App\User;
use Auth;
class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */
    public function index(User $model)
    {
        if(Auth::user()->level < 100) abort(403, 'Unauthorized action.');
        return view('users.index', ['users' => $model->paginate(15)]);
    }

    /**
     * Show the form for creating a new user
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $model
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request, User $model)
    {
        $model->create($request->merge(['password' => Hash::make($request->get('password'))])->all());
        return redirect()->route('user.index')->withStatus(__('User successfully created.'));
    }

    /**
     * Show the form for editing the specified user
     *
     * @param  \App\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request, User  $user)
    {
        $hasPassword = $request->get('password');
        $user->update(
            $request->merge(['password' => Hash::make($request->get('password'))])
            ->except([$hasPassword ? '' : 'password']
        ));
        $user->line=$request->input('line');
        $user->facebook=$request->input('facebook');

        if($request->hasFile('avatar')) $fileNameToStore=uploadImage($request->file('avatar'),'avatar');
        $fileNameToStore='';

//        if ($fileNameToStore) $user->avatar=$fileNameToStore;

        if (isset($fileNameToStore)) {
            if (!empty($user->avatar)) {
                $cover_path = public_path().'/avatar/'.$user->avatar;
                if (is_file($cover_path)) { unlink($cover_path); }      
                $user->avatar = $fileNameToStore;           
            }
            else $user->avatar = $fileNameToStore;
        }

        $user->level=$request->input('level');
        $user->save();
        return redirect()->route('user.index')->withStatus(__('User successfully updated.'));
    }

    /**
     * Remove the specified user from storage
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User  $user)
    {
        $user->delete();
        return redirect()->route('user.index')->withStatus(__('User successfully deleted.'));
    }
}
