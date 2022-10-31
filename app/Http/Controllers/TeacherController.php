<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;
use Session;

class TeacherController extends Controller
{
    public $teacher, $image, $imageName, $directory, $imgUrl, $existingPassword, $teacherInfo;
    public function index(){
        return view('admin.teacher.add-teacher');
    }public function saveTeacher(Request $request){
        $this->teacher=new Teacher();
        $this->teacher->name = $request->name;
        $this->teacher->phone = $request->phone;
        $this->teacher->email = $request->email;
        $this->teacher->password = bcrypt(12345678);
        $this->teacher->address = $request->address;
        $this->teacher->image = $this->saveImage($request);
        $this->teacher->save();
        return back()->with('message','Teacher Info Save Successfully');

//        return $request->file('image');
    }
    private function saveImage($request){
        $this->image=$request->file('image');
        $this->imageName=rand().'.'.$this->image->getClientOriginalExtension();
        $this->directory='adminAsset/teacher-image/';
        $this->imgUrl=$this->directory.$this->imageName;
        $this->image->move($this->directory, $this->imageName);
        return $this->imgUrl;
    }
    public function manageTeacher(){
        return view('admin.teacher.manage-teacher',[
            'teachers'=>Teacher::all()
        ]);
    }
    public function teacherLoginForm(){
        return view('admin.teacher.login');
    }
    public function teacherLoginCheck(Request $request){
        $this->teacherInfo=Teacher::where('email',$request->user_name)
            ->orWhere('phone',$request->user_name)
            ->first();
        if($this->teacherInfo){
            $this->existingPassword=$this->teacherInfo->password;
            if (password_verify($request->password,$this->existingPassword)){
                Session::put('teacherId',$this->teacherInfo->id);
                Session::put('teacherName',$this->teacherInfo->name);
                return redirect('/');
            }else{
                return back()->with('message','please use valid password');

            }
        }else{
            return back()->with('message','please use valid email or phone');
        }

    }
    public function teacherLogout(){
        Session::forget('teacherId');
        Session::forget('teacherName');
        return redirect('/');

    }
    public function teacherProfile(){
        return view('admin.teacher.profile');
    }

}
