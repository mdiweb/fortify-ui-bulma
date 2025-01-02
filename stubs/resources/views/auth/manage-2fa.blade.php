@extends('dashboard._layout')

@section('scriptFoot')
    <script>
        const contentProps = {
            qrCode: '{!! $qrCode !!}',
            qrCodeUrl: '{{ $qrCodeUrl }}',
            tfaConfirmed: @json($tfaConfirmed),
            recoveryCodes: @json($recoveryCodes),
            clientName: '{{ Config::get('app.client_name') }}',
        };
    </script>
    <script src="/admin/js/auth/manage-2fa.js"></script>
@endsection
