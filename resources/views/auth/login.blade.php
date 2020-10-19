@extends('indigo-layout::auth2')

@section('meta_title', _p('auth::pages.login.meta_title', 'Sign in') . ' - ' . config('app.name'))
@section('meta_description', _p('auth::pages.login.meta_description', 'Sign in user to system'))

@push('head')

@endpush

@section('title')
    <h2>{{ _p('auth::pages.login.headline', 'Sign in') }}</h2>
@endsection

@section('content')
    @include('indigo-layout::auth.login')
@endsection

@section('footer')
    {!! _p('auth::pages.login.footer-headline', 'Don\'t have an account? <a href=":link_url">:link_name</a> ', ['link_url' => route('register'), 'link_name' => _p('auth::pages.login.sign_up', 'Sign up')]) !!}
@endsection
