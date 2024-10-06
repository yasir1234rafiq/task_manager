@extends('dashboard')
@section('content')
<div class="container mt-2">
   <div class="row">
      <div class="col-lg-12 margin-tb">
         <div class="pull-left">
            <h2>Edit Task</h2>
         </div>
         <div class="pull-right">
            <a class="btn btn-primary" href="{{ route('tasks.index') }}" enctype="multipart/form-data"> Back</a>
         </div>
      </div>
   </div>
   @if(session('status'))
   <div class="alert alert-success mb-1 mt-1">
      {{ session('status') }}
   </div>
   @endif
   <form action="{{ route('tasks.update',$task->id) }}" method="post" enctype="multipart/form-data">
      @csrf
      @method('PUT')
      <div class="row">
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
               <strong>Task Title:</strong>
               <input type="text" name="title" value="{{ $task->title }}" class="form-control" placeholder="task Title">
               @error('title')
               <div class="alert alert-danger mt-1 mb-1">{{ $message}}</div>
               @enderror
            </div>
         </div>
         <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
               <strong>Task Description:</strong>
               <textarea class="form-control" style="height:150px" name="description" placeholder="Task Description">{{ $task->description}}</textarea>
               @error('description')
               <div class="alert alert-danger mt-1 mb-1">{{ $message }}</div>
               @enderror
            </div>
         </div>

         <button type="submit" class="btn btn-primary ml-3">Submit</button>
      </div>
   </form>
</div>
@endsection
