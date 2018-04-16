<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Mail\UserCreated;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.cradentials')->only(['store','resend']);
        $this->middleware('transform.input:'. UserTransformer::class)->only(['store','update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return $this->showAll($users);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ];

        $this->validate($request,$rules);

        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();
        $data['admin'] = User::REGULAR_USER;

        $user = User::create($data);

        return response()->json(['data'=>$user],201);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne($user);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $rules = [
            'email' => 'email|unique:users,email',
            'password' => 'min:6|confirmed',
            'admin' => 'in:'.User::ADMIN_USER . ',' . User::REGULAR_USER
        ];
         $this->validate($request , $rules);

        if($request->has('name'))
        {
            $user->name = $request->name;
        }
        if($request->has('email') && $user->email != $request->email)
        {
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        }

        if($request->has('password'))
        {
            $user->password = bcrypt($request->password);
        }

        if ($request->has('admin'))
        {
            if(!$user->isVerified())
            {
                return $this->errorResponse(['error'=>'Only veridied user can modified admin field'],409);
            }
            $user->admin = $request->admin;
        }

        if(!$user->isDirty())
        {
            return $this->errorResponse(['error'=>'You need to specify different value to update'],422);
        }
        $user->save();
        return response()->json(['data'=>$user , 'message'=>'User updated successfully'],201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message'=>'User deleted successfully']);
    }

    public function verify($token)
    {
        $user = User::where('verification_token',$token)->firstOrFail();
        $user->verified = User::VERIFIED_USER;
        $user->verification_token = null;
        $user->save();
        return $this->showMessage('User has been successfully verified');


    }

    public function resend(User $user)
    {
        if($user->isVerified())
        {
            return $this->errorResponse('This user is already verified',409);
            }
        Mail::to($user->email)->send(new UserCreated($user));

        return $this->showMessage('The verification email has been resent ');


    }
}
