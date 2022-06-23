<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\MockObject\Api;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category = Category::all();
        return response()->json($category);
    }

    public function getCategoryMovie()
    {
        $category = Category::with('Movies')->get();
        return response()->json($category);
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
        $user = Auth::user()->admin;
//        dd($user);
        if ($user == 1){
            $validator = Validator::make($request->all(),[
                'name'=>'required|unique:categories'
            ],[],[
                'name'=>'الأسم'
            ]);

            if ($validator->fails()){
                $msg = 'تأكد من البيانات المدخلة';
                $data = $validator->errors();
                return response()->json(compact('msg','data'),422);
            }

            $category = new Category();
            $category->name = $request->name;
            $category->save();
            return response()->json(['msg','تمت عملية الإضافة بنجاح']);

        }else{
            return response()->json(['ليس لديك الصلاحية للإضافة']);
        }
//        return response()->json($user);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        return response()->json($category);
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
        $user = Auth::user()->admin;
//        dd($user);
        if ($user == 1){
        $validator = Validator::make($request->all(),[
            'name'=>'required|unique:categories,name,'.$id
        ],[],[
            'name'=>'الأسم'
        ]);

        if ($validator->fails()){
            $msg = 'تأكد من البيانات المدخلة';
            $data = $validator->errors();
            return response()->json(compact('msg','data'),422);
        }

        $category = Category::find($id);
        $category->name = $request->name;
        $category->save();
        return response()->json(['msg','تمت عملية التعديل بنجاح']);
        }else{
            return response()->json(['ليس لديك الصلاحية للتعديل']);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $search = Category::find($id);
//        dd($search);
        if ($search) {
            $user = Auth::user()->admin;
//        dd($user);
            if ($user == 1) {
                Category::where('id', '=', $id)->delete();
                return response()->json(['نمت عملية الحذف بنجاح']);
            } else {
                return response()->json(['ليس لديك الصلاحية للحذف']);
            }
        }else{
            return response()->json(['الحقل محذوف بالفعل']);
        }
    }
}
