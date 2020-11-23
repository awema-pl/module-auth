@extends('indigo-layout::auth2')

@section('meta_title', _p('auth::pages.verify.meta_title', 'Verify Your Email Address') . ' - ' . config('app.name'))
@section('meta_description', _p('auth::pages.verify.meta_description', 'Verify your email address for full access of user'))

@push('head')

@endpush

@section('title')
    <h2>{{ _p('auth::pages.verify.headline', 'Verify Your Email Address') }}</h2>
@endsection

@section('content')
    @if (session('resent'))
        <div class="alert alert-success" role="alert">
            {{ __('A fresh verification link has been sent to your email address.') }}
        </div>
    @endif

    {{ __('Before proceeding, please check your email for a verification link.') }}

    @include('indigo-layout::auth.verify')
@endsection

@section('footer')
    {!! _p('auth::pages.verify.footer-headline', '<a href=":link_url" class="awema-spa-ignore">:link_name</a> ', ['link_url' => route('logout'), 'link_name' => _p('auth::pages.verify.logout', 'Logout')]) !!}
@endsection

