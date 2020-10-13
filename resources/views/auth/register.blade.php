@extends('indigo-layout::auth2')

@section('meta_title', _p('auth::pages.register.meta_title', 'Create an Account') . ' - ' . config('app.name'))
@section('meta_description', _p('auth::pages.register.meta_description', 'Awema Platform Demo'))

@push('head')

@endpush

@section('title')
    <h2>{{ _p('auth::pages.register.headline', 'Create Your Account') }}</h2>
@endsection

@section('content')
    @include('indigo-layout::auth.register')
@endsection

@section('footer')
    {!! _p('auth::pages.register.footer-headline', 'Already have an account? <a href=":link_url">:link_name</a> ', ['link_url' => route('login'), 'link_name' => _p('auth::pages.register.sign_in', 'Sign in')]) !!}
@endsection
