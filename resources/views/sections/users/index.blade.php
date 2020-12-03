@extends('indigo-layout::main')

@section('meta_title', _p('auth::pages.user.meta_title', 'Users') . ' - ' . config('app.name'))
@section('meta_description', _p('auth::pages.user.meta_description', 'Users in application'))

@push('head')

@endpush

@section('title')
    {{ _p('auth::pages.user.headline', 'Users') }}
@endsection

@section('create_button')
    <button class="frame__header-add" @click="AWEMA.emit('modal::create:open')" title="{{ _p('auth::pages.user.create_user', 'Create user') }}"><i class="icon icon-plus"></i></button>
@endsection

@section('content')
    <div class="grid">
        <div class="cell-1-1 cell--dsm">
            <h4>{{ _p('auth::pages.user.users', 'Users') }}</h4>
            <div class="card">
                <div class="card-body">
                    <content-wrapper :url="$url.urlFromOnlyQuery('{{ route('auth.user.scope')}}', ['page', 'limit'], $route.query)"
                        :check-empty="function(test) { return !(test && (test.data && test.data.length || test.length)) }"
                        name="users_table">
                        <template slot-scope="table">
                            <table-builder :default="table.data">
                                <tb-column name="name" label="{{ _p('auth::pages.user.name', 'Name') }}"></tb-column>
                                <tb-column name="email" label="{{ _p('auth::pages.user.email', 'E-mail') }}"></tb-column>
                                <tb-column name="email_verified_at" label="{{ _p('auth::pages.user.email_verified', 'E-mail verified') }}">
                                    <template slot-scope="col">
                                        <template v-if="col.data.email_verified_at">
                                            @{{ col.data.email_verified_at }}
                                        </template>
                                        <template v-if="!col.data.email_verified_at">
                                            <span class="cl-red">{{ _p('auth::pages.user.no', 'No') }}</span>
                                        </template>
                                    </template>
                                </tb-column>
                                <tb-column name="created_at" label="{{ _p('auth::pages.user.created_at', 'Created at') }}"></tb-column>
                                <tb-column name="manage" label="{{ _p('auth::pages.user.options', 'Options')  }}">
                                    <template slot-scope="col">
                                        <context-menu right boundary="table">
                                            <button type="submit" slot="toggler" class="btn">
                                                {{_p('auth::pages.user.options', 'Options')}}
                                            </button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'editUser', data: col.data}); AWEMA.emit('modal::edit_user:open')">
                                                {{_p('auth::pages.user.edit', 'Edit')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'changePasswordUser', data: col.data}); AWEMA.emit('modal::change_password_user:open')">
                                                {{_p('auth::pages.user.change_password', 'Change password')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'switchUser', data: col.data}); AWEMA.emit('modal::switch_user:open')">
                                                {{_p('auth::pages.user.switch_user', 'Switch to user')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'deleteUser', data: col.data}); AWEMA.emit('modal::delete_user:open')">
                                                {{_p('auth::pages.user.delete', 'Delete')}}
                                            </cm-button>
                                        </context-menu>
                                    </template>
                                </tb-column>
                            </table-builder>

                            <paginate-builder v-if="table.data"
                                :meta="table.meta"
                            ></paginate-builder>
                        </template>
                        @include('indigo-layout::components.base.loading')
                        @include('indigo-layout::components.base.empty')
                        @include('indigo-layout::components.base.error')
                    </content-wrapper>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    <modal-window name="create" class="modal_formbuilder" title="{{ _p('auth::pages.user.create_user', 'Create user') }}">
        <form-builder url="{{ route('auth.user.store') }}" @sended="AWEMA.emit('content::users_table:update')">
            <div class="grid">
                <div class="cell">
                    <fb-input name="name" label="{{ _p('auth::pages.user.name', 'Name') }}" autocomplete="name"></fb-input>
                    <fb-input name="email" type="email" label="{{ _p('auth::pages.user.email', 'E-mail') }}" autocomplete="email"></fb-input>
                    <fb-input name="password" type="password" label="{{ _p('auth::pages.user.password', 'Password') }}" autocomplete="new-password"></fb-input>
                    <fb-input name="password_confirmation" type="password" label="{{ _p('auth::pages.user.confirm_password', 'Confirm password') }}" autocomplete="new-password"></fb-input>
                    <fb-date name="email_verified_at" format="YYYY-MM-DD HH:mm:ss" label="{{ _p('auth::pages.user.email_verified', 'E-mail verified') }}"></fb-date>
                </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="edit_user" class="modal_formbuilder" title="{{ _p('auth::pages.user.edit_user', 'Edit user') }}">
        <form-builder url="{{ route('auth.user.update') }}/{id}" method="patch"
                      @sended="AWEMA.emit('content::users_table:update')"
                      send-text="{{ _p('auth::pages.user.save', 'Save') }}" store-data="editUser">
            <div class="grid" v-if="AWEMA._store.state.editUser">
                <div class="cell">
                    <fb-input name="name" label="{{ _p('auth::pages.user.name', 'Name') }}"></fb-input>
                    <fb-date name="email_verified_at" format="YYYY-MM-DD HH:mm:ss" label="{{ _p('auth::pages.user.email_verified_at', 'E-mail verified') }}"></fb-date>
                </div>
            </div>
        </form-builder>
    </modal-window>

    <modal-window name="change_password_user" class="modal_formbuilder" title="{{ _p('auth::pages.user.change_password', 'Change password') }}">
        <form-builder url="{{ route('auth.user.change_password') }}/{id}" method="patch"
                      @sended="AWEMA.emit('content::users_table:update')"
                      send-text="{{ _p('auth::pages.user.change', 'Change') }}" store-data="changePasswordUser">
            <div class="grid" v-if="AWEMA._store.state.changePasswordUser">
                <div class="cell">
                    <fb-input name="password" type="password" label="{{ _p('auth::pages.user.password', 'Password') }}" autocomplete="new-password"></fb-input>
                    <fb-input name="password_confirmation" type="password" label="{{ _p('auth::pages.user.confirm_password', 'Confirm password') }}" autocomplete="new-password"></fb-input>
                </div>
            </div>
        </form-builder>
    </modal-window>
    <modal-window name="switch_user" class="modal_formbuilder" title="{{  _p('auth::pages.user.are_you_sure_switch', 'Are you sure switch?') }}">
        <form-builder :edited="true" url="{{route('auth.user.switch') }}" method="post"
                      send-text="{{ _p('auth::pages.user.confirm', 'Confirm') }}" store-data="switchUser"
                      disabled-dialog>
                <fb-input name="id" type="hidden"></fb-input>
        </form-builder>
    </modal-window>
    <modal-window name="delete_user" class="modal_formbuilder" title="{{  _p('auth::pages.user.are_you_sure_delete', 'Are you sure delete?') }}">
        <form-builder :edited="true" url="{{route('auth.user.delete') }}/{id}" method="delete"
                      @sended="AWEMA.emit('content::users_table:update')"
                      send-text="{{ _p('auth::pages.user.confirm', 'Confirm') }}" store-data="deleteUser"
                      disabled-dialog>

        </form-builder>
    </modal-window>
@endsection
