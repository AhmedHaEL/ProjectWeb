<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $user = User::with('getImageUelAttribute')->get();
        $user = User::get();
        return response()->json($user);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
           'email'=>'required|unique:users',
           'password'=> 'required|min:8',
           'image_user'=>'mimes:jpeg,jpg,png,gif|sometimes|max:10000',
           'admin'=>'sometimes|numeric|min:0|max:1'
        ],[],[
            'email'=>'البريد الإلكتروني',
            'password'=>'كلمة المرور',
            'image_user'=>'صورة المستخدم',
            'admin'=>'نوع الحساب'
        ]);

        if ($validator->fails()){
            $data = $validator->errors();
            $msg = 'تأكد من البيانات المدخلة';
            return response()->json(compact('msg','data'),422);
        }

        $user = new User();

        if ($request->hasFile('image_user')){
            $file = $request->file('image_user');
            $image_name = time().'.'.$file->getClientOriginalExtension();
            $path = 'images'.'/'.$image_name;
            $file->move(public_path('images'),$image_name);
            $user->image_user = $path;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->admin){
            $user->admin = $request->admin;
        }else{
            $user->admin = 0;
        }


        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json(['msg'=>'تمت عملية الإضافة بنجاح']);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email'=>'required',
            'password'=>'required'
        ],[],[
            'email'=>'البريد الإلكتروني',
            'password'=>'كلمة المرور'
        ]);

        if ($validator->fails()){
            $msg = 'تأكد من البيانات المدخلة';
            $data = $validator->errors();
            return response()->json(compact('msg','data'),422);
        }

        $user = User::where('email',$request->email)->first();

        if (!$user){
            return response()->json(['هذا اللإيميل غير موخود'],401);
        }

        if (Hash::check($request->password,$user->password)){
            $token =$user->createToken('Laravel password Grant Clint');
            $response =['token'=>$token];
            return response($response,200);
        }else{
            $response = ['message'=>'خطأ في كلمة المرور'];
            return response($response,422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $validator = Validator::make($request->all(),[
            'name'=>'sometimes',
            'email'=>'sometimes|unique:users,email,'.$id,
            'password'=> 'min:8|sometimes',
            'image_user'=>'mimes:jpeg,jpg,png,gif|sometimes|max:10000',
           'admin'=>'sometimes|numeric|min:0|max:1'
        ],[],[
            'name'=>'الأسم',
            'email'=>'البريد الإلكتروني',
            'password'=>'كلمة المرور',
            'image_user'=>'صورة المستخدم',
            'admin'=>'نوع الحساب'
        ]);

        if ($validator->fails()){
            $data = $validator->errors();
            $msg = 'تأكد من البيانات المدخلة';
            return response()->json(compact('msg','data'),422);
        }

        $user = User::find($id);

        if ($request->hasFile('image_user')){
            $file = $request->file('image_user');
            $image_name = time().'.'.$file->getClientOriginalExtension();
            $path = 'images'.'/'.$image_name;
            $file->move(public_path('images'),$image_name);
            $user->image_user = $path;
        }
        if ($request->name){
            $user->name = $request->name;
        }
        if ($request->email){
            $user->email = $request->email;
        }
        if (Hash::make($request->password)){
            $user->password = Hash::make($request->password);
        }
        if ($request->admin) {
            $user->admin = $request->admin;
        }
        $user->save();
        return response()->json(['msg'=>'تمت عملية التعديل بنجاح']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $search = User::find($id);
//        dd($search);
        if ($search) {
        User::where('id','=',$id)->delete();
        return response()->json(['تمت عملية الحذف بنجاح']);
        }else{
            return response()->json(['الحقل محذوف بالفعل']);
        }
    }
}
