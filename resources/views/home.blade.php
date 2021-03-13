@extends('layouts.app')
@section('content')
    <div>
        <button id="youtube">YouTube</button>
        <button id="google">Google</button>
    </div>
    <div id="report" data-report-id="">
        
    </div>
@endsection
@section('scripts')
    <script>
        document.querySelector('#youtube').addEventListener('click', (e) => {
            clickHandle(e.target.id)
        })
        document.querySelector('#google').addEventListener('click', (e) => {
            clickHandle(e.target.id)
        })
        function clickHandle(url){
            fetch('/apireports/public/'+ url, {
                    method: 'POST',
                    headers: {
                        "X-CSRF-Token": '{{ csrf_token() }}',
                        "Accept": "application/json",
                    }
                })
                .then(
                    response => response.json()
                )
                .then(
                    response => document.querySelector('#report').innerText = JSON.stringify(response)
                )
        }
    </script>
@endsection
