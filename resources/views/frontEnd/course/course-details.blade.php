@extends('frontEnd.master')
@section('content')
    <div class="section margin-top_50 silver_bg" style="margin-top: 106px">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="full float-right_img">
                        <img src="{{asset($course->image)}}" alt="#" />
                    </div>
                </div>
                <div class="col-md-6 layout_padding_2">
                    <div class="full">
                        <div class="heading_main text_align_left">
                            <h2><span>Apply for Admission</span></h2>
                        </div>
                        <div class="full">
                            <h5>Course Name :{{ $course->course_name }}</h5>
                            <h5>Teacher Name :{{ $course->name }}</h5>
                            <h5>Teacher Phone :{{ $course->phone }}</h5>
                            <h5>Teacher Email :{{ $course->email }}</h5>
                            <p>Description: {{ $course->description }}</p>
                        </div>
                        <div class="full">
                            @if(Session::get('studentId'))
                                <button class="hvr-radial-out button-theme" data-toggle="modal" data-target="#exampleModal">Apply</button>
                            @else
                                <a class="hvr-radial-out button-theme" href="{{ route('student-login') }}">Login</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admission') }}" method="post">
                        @csrf
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                        <input type="hidden" name="student_id" value="{{ Session::get('studentId') }}">
                        <input type="checkbox" name="confirmation" value="1">are you sure Apply for this course?
                        <input type="submit" value="submit">
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
