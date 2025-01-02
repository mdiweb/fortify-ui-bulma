@extends('dashboard._layout')

@section('scriptFoot')
    <script>
        const contentProps = {
            csrfToken: '{{ csrf_token() }}',
        };
    </script>
    <script src="/admin/js/auth/update-password.js"></script>
@endsection
