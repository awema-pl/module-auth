@extends('indigo-layout::auth2')

@section('meta_title', _p('pages.login.meta_title', 'Logowanie') . ' - ' . config('app.name'))
@section('meta_description', _p('pages.login.meta_description', 'Logowanie do panelu użytkownika NetLinker'))

@push('head')

@endpush

@section('title')
    <h2>{{ _p('pages.login.headline', 'Logowanie') }}</h2>
@endsection

@section('content')
    @include('indigo-layout::auth.login')
@endsection

@section('footer')
    {!! _p('pages.login.footer-headline', 'Don\'t have an account? <a href=":link_url">:link_name</a> ', ['link_url' => route('register'), 'link_name' => _p('pages.register.sign_up')]) !!}
@endsection
