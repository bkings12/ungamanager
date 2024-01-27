@extends('layouts.master')
@extends('layouts.app')


@section('title', 'Generate Report')

@section('content')
<div class="container">
        <h1>Generate Report</h1>
        <form method="POST" action="{{ route('admin.generate') }}">
            @csrf
            <div class="form-group">
                <label for="startDate">Start Date:</label>
                <input type="text" id="startDate" name="startDate" required>
            </div>
            <div class="form-group">
                <label for="endDate">End Date:</label>
                <input type="text" id="endDate" name="endDate" required>
            </div>
            <button type="submit" class="btn btn-primary">Generate Report</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script>
        $(function() {
            $("#startDate, #endDate").datepicker({
                dateFormat: 'yy-mm-dd'
            });
        });
    </script>
@endsection


