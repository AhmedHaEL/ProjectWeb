<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Nette\Utils\DateTime;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $movie = Movie::all();
        return response()->json($movie);
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
        if ($user == 1){
            $validator = Validator::make($request->all(),[
            'category_id'=>'required',
            'name'=>'required|unique:movies',
            'show_time'=>'required',
            'image'=>'mimes:jpeg,jpg,png,gif|sometimes|max:10000'
            ],[],[
            'category_id'=>'نوع التصنيف',
            'name'=>'الأسم',
            'show_time'=>'وقت العرض',
            'image'=>'صورة الفيلم'
            ]);

            if ($validator->fails()){
            $msg = 'تأكد من البيانات المدخلة';
            $data = $validator->errors();
            return response()->json(compact('msg','data'),422);
            }

            $movie = new Movie();
            $movie->category_id = $request->category_id;

            if ($request->hasFile('image')){
            $file = $request->file('image');
            $image_name = time().'.'.$file->getClientOriginalExtension();
            $path = 'images'.'/'.$image_name;
            $file->move(public_path('images'),$image_name);
            $movie->image = $path;
            }

            $movie->name = $request->name;
            $movie->show_time = $request->show_time;
            $movie->save();
            return response()->json(['msg','تمت عملية الإضافة بنجاح']);
        }else{
            return response()->json(['ليس لديك الصلاحية للإضافة']);
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
        $movie = Movie::find($id);
        return response()->json($movie);
    }

    public function searchMovieName($name)
    {
        $movie = Movie::where('name','like','%'.$name.'%')->first();
        return response()->json($movie);
    }
    public function movieMonth()
    {
        $movie = Movie::where('show_time','like','%'.date("m").'%')->
                        where('show_time','like','%'.date("y").'%')->get();
        return response()->json($movie);
    }
    public function searchMovieDaye()
    {
        $movie = Movie::whereDate('show_time',Carbon::today())->get();
        return response()->json($movie);
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

        if ($user == 1){
            $validator = Validator::make($request->all(),[
                 'category_id'=>'sometimes',
                 'name'=>'sometimes|unique:movies,name,'.$id,
                 'show_time'=>'sometimes'
               ],[],[
                 'category_id'=>'نوع التصنيف',
                 'name'=>'الأسم',
                 'show_time'=>'وقت العرض'
            ]);

            if ($validator->fails()){
                $msg = 'تأكد من البيانات المدخلة';
                $data = $validator->errors();
                return response()->json(compact('msg','data'),422);
            }

            $movie = Movie::find($id);

            if ($request->category_id){
                 $movie->category_id = $request->category_id;
            }
            if ($request->name){
                 $movie->name = $request->name;
            }
            if ($request->show_time){
                $movie->show_time = $request->show_time;
            }

            $movie->save();
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
        $search = Movie::find($id);
       if ($search) {
            $user = Auth::user()->admin;

            if ($user == 1){
                 Movie::where('id','=',$id)->delete();
                 return response()->json(['تمت عملية الحذف بنجاح']);
            }else{
                 return response()->json(['ليس لديك الصلاحية للحذف']);
            }

       }else{
            return response()->json(['الحقل محذوف بالفعل أو غير موجود']);
       }
    }
}
