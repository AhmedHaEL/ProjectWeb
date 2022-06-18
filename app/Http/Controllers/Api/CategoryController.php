<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
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

        $category = Category::find($id);
        $category->name = $request->name;
        $category->save();
        return response()->json(['msg','تمت عملية التعديل بنجاح']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Category::where('id','=',$id)->delete();
        return response()->json(['نمت عملية الحذف بنجاح']);
    }
}
