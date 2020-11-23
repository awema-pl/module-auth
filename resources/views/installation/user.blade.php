@extends('indigo-layout::installation')

@section('meta_title', _p('auth::pages.installation.user.meta_title', 'Installation first user') . ' - ' . config('app.name'))
@section('meta_description', _p('auth::pages.installation.user.meta_description', 'Installation first user to system'))

@push('head')

@endpush

@section('title')
    <h2>{{ _p('auth::pages.installation.user.headline', 'Installation first user') }}</h2>
@endsection

@section('content')
    @include('indigo-layout::auth.register')
@endsection
