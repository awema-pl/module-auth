@extends('indigo-layout::auth2')

@section('meta_title', _p('auth::pages.password.email.meta_title', 'Reset Password') . ' - ' . config('app.name'))
@section('meta_description', _p('auth::pages.password.email.meta_description', 'Restart password of user.'))

@push('head')

@endpush

@section('title')
    <h2>{{ _p('auth::pages.password.email.headline', 'Reset Password') }}</h2>
@endsection

@section('content')
    @include('indigo-layout::auth.passwords.email')
@endsection

@section('footer')
    {!! _p('auth::pages.password.email.footer-headline', '<a href=":link_url">:link_name</a> ', ['link_url' => route('login'), 'link_name' => _p('auth::pages.password.email.back_to_sign_in', 'Back to sign in')]) !!}
@endsection