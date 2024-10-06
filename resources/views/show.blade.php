
@extends('dashboard')

@section('content')
    <div class="container">
        <h2>{{ $task->title }}</h2>
        <h5><strong>Description:</strong> {{ $task->description }}</h5>
        <h5><strong>Status:</strong> {{ $task->status }}</h5>

        @if($task->status !== 'Completed')
            <form action="{{ route('task.complete', $task->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success">Complete Task</button>
            </form>
        @endif

        <hr>

        <h4>Feedback</h4>
        <div id="feedbackList">
            @foreach($task->feedback as $comment)
                <div>
                    <strong>{{ $comment->user->name }}:</strong>
                    <p>{{ $comment->comment }}</p>
                </div>
            @endforeach
        </div>

        <div id="feedbackMessage"></div> <!-- For displaying success or error messages -->

        <form action="{{ route('task.feedback', $task->id) }}" method="POST" id="feedbackForm">
            @csrf
            <div class="form-group">
                <label for="comment">Leave Feedback:</label>
                <textarea name="comment" id="comment" class="form-control" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Feedback</button>
        </form>

        <!-- Include jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#feedbackForm').on('submit', function(event) {
                    event.preventDefault(); // Prevent the default form submission

                    $.ajax({
                        url: $(this).attr('action'), // Get the form action URL
                        type: 'POST',
                        data: $(this).serialize(), // Serialize form data
                        success: function(response) {
                            // Handle success response

                            $('#comment').val(''); // Clear the textarea

                            // Append the new comment to the feedback list
                            $('#feedbackList').append('<div><strong>You:</strong><p>' + response.comment + '</p></div>');
                        },

                    });
                });
            });
        </script>
@endsection
