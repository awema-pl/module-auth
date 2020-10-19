@extends('indigo-layout::auth2')

@section('meta_title', _p('auth::pages.password.reset.meta_title', 'Reset Password') . ' - ' . config('app.name'))
@section('meta_description', _p('auth::pages.password.reset.meta_description', 'Restart password of user.'))

@push('head')

@endpush

@section('title')
    <h2>{{ _p('auth::pages.password.reset.headline', 'Reset Password') }}</h2>
@endsection

@section('content')
    @include('indigo-layout::auth.passwords.reset')
@endsection

{{--@section('footer')--}}

{{--@endsection--}}
