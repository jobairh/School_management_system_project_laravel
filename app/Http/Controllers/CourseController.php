<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use DB;
use Session;

class CourseController extends Controller
{
    public $image, $imageName, $directory, $imgUrl;
    public function addCourse(){
        return view('admin.course.add-course');
    }
    public function manageCourse(){
        $courses=DB::table('courses')
            ->join('teachers','courses.teacher_id','teachers.id')
            ->select('courses.*','teachers.name')
            ->get();

        return view('admin.course.manage-course',[
            'courses'=>$courses
//            'courses'=>Course::all()
        ]);
    }
    public function manageStudent(){
        $students=DB::table('students')
            ->join('courses','students.id','courses.id')
            ->select('students.*','courses.course_name')
            ->get();

        return view('admin.course.manage-student',[
            'students'=>$students
        ]);
    }
    public function saveCourse(Request $request){
        $this->validate($request,[
            'course_name'=>'required|unique:courses,course_name|string|min:10|max:50',
            'slug'=>'required|unique:courses,slug|string|min:10|max:50',
            'image'=> 'required|mimes:jpg,bmp,png'
        ]);
        $course=new Course();
        $course->teacher_id=$request->teacher_id;
        $course->course_name=$request->course_name;
        $course->slug=$this->makeSlug($request);
        $course->course_code=$request->course_code;
        $course->description=$request->description;
        $course->image=$this->saveImage($request);
        $course->save();
        return back();
    }
    public function makeSlug($request){
        if ($request->slug){
            $str=$request->slug;
            return preg_replace('/\s+/u','-',trim($str));
        }
        $str=$request->course_name;
        return preg_replace('/\s+/u','-',trim($str));
    }
    private function saveImage($request){
        $this->image=$request->file('image');
        $this->imageName=rand().'.'.$this->image->getClientOriginalExtension();
        $this->directory='adminAsset/course-image/';
        $this->imgUrl=$this->directory.$this->imageName;
        $this->image->move($this->directory, $this->imageName);
        return $this->imgUrl;
    }
    public function manageApplicant(){
        $applicants=DB::table('admissions')
            ->join('students','admissions.student_id','students.id')
            ->join('courses','admissions.course_id','courses.id')
            ->select('students.student_name','students.student_email',
                'students.student_phone','courses.course_name',
                'courses.course_code','courses.teacher_id',
                'admissions.id','admissions.confirmation')
            ->where('courses.teacher_id','=',Session::get('teacherId'))
            ->get();
//        return $applicants;

        return view('admin.teacher.manage-applicant',[
            'applicants'=>$applicants
        ]);
    }
}
