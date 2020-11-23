@extends('indigo-layout::main')

@section('meta_title', _p('auth::pages.token.meta_title', 'API tokens') . ' - ' . config('app.name'))
@section('meta_description', _p('auth::pages.token.meta_description', 'API tokens in application'))

@push('head')

@endpush

@section('title')
    {{ _p('auth::pages.token.headline', 'API tokens') }}
@endsection

@section('create_button')
    <button class="frame__header-add" @click="AWEMA.emit('modal::create:open')" title="{{ _p('auth::pages.token.create_token', 'Create token') }}"><i class="icon icon-plus"></i></button>
@endsection

@section('content')
    <div class="grid">
        <div class="cell-1-1 cell--dsm">
            <h4>{{ _p('auth::pages.token.tokens', 'API Tokens') }}</h4>
            <div class="card">
                <div class="card-body">
                    <div class="text-right">
                        <button class="btn btn_second" @click="$refs.searching.toggle()">{{ _p('auth::pages.token.searching', 'Searching') }}</button>
                    </div>
                     <slide-up-down ref="searching">
                         <filter-wrapper class="section" name="searching">
                             <fb-input name="user" size-sm label="{{ _p('auth::pages.token.user', 'User') }}"></fb-input>
                             <fb-input name="name" size-sm label="{{ _p('auth::pages.token.name', 'Name') }}"></fb-input>
                         </filter-wrapper>
                    </slide-up-down>
                    <content-wrapper class="section" :url="$url.urlFromOnlyQuery('{{ route('auth.token.scope')}}', ['page', 'limit', 'user', 'name'], $route.query)"
                        :check-empty="function(test) { return !(test && (test.data && test.data.length || test.length)) }"
                        name="tokens_table">
                        <template slot-scope="table">
                            <table-builder :default="table.data">
                                <tb-column name="user" label="{{ _p('auth::pages.token.user', 'User') }}">
                                    <template slot-scope="col">
                                        @{{ col.data.user.email }}
                                    </template>
                                </tb-column>
                                <tb-column name="name" label="{{ _p('auth::pages.token.name', 'Name') }}"></tb-column>
                               <tb-column name="last_used_at" label="{{ _p('auth::pages.token.last_used_at', 'Last used at') }}"></tb-column>
                                <tb-column name="created_at" label="{{ _p('auth::pages.token.created_at', 'Created at') }}"></tb-column>
                                <tb-column name="manage" label="{{ _p('auth::pages.token.options', 'Options')  }}">
                                    <template slot-scope="col">
                                        <context-menu right boundary="table">
                                            <button type="submit" slot="toggler" class="btn">
                                                {{_p('auth::pages.token.options', 'Options')}}
                                            </button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'showToken', data: col.data}); AWEMA.emit('modal::show_token:open')">
                                                {{_p('auth::pages.token.show_token', 'Show token')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'changeToken', data: col.data}); AWEMA.emit('modal::change_token:open')">
                                                {{_p('auth::pages.token.change_token', 'Change token')}}
                                            </cm-button>
                                            <cm-button @click="AWEMA._store.commit('setData', {param: 'deleteToken', data: col.data}); AWEMA.emit('modal::delete_token:open')">
                                                {{_p('auth::pages.token.delete_token', 'Delete token')}}
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

    <content-window name="create" class="modal_formbuilder" title="{{ _p('auth::pages.token.create_token', 'Create token') }}">
        <form-builder url="{{ route('auth.token.store') }}" @sended="AWEMA.emit('content::tokens_table:update')"
                      send-text="{{ _p('auth::pages.token.create', 'Utwórz') }}">
            <div class="grid">
                <div class="cell">
                    <fb-select name="user_id" :multiple="false" url="{{ route('auth.user.scope') }}?q=%s" open-fetch options-value="id" options-name="email"
                               label="{{ _p('auth::pages.token.select_user', 'Select user') }}">
                    </fb-select>
                    <fb-input name="name" :value="'{{config('awemapl-auth.default_name_token')}}'" label="{{ _p('auth::pages.token.name', 'Name') }}"></fb-input>
                </div>
            </div>
        </form-builder>
    </content-window>

    <content-window name="show_token" class="modal_formbuilder" title="{{  _p('auth::pages.token.token', 'Token') }}">
        <form-builder :edited="true" url="" @send="AWEMA.emit('modal::show_token:close');" :hide-cancel-button="true"
                      send-text="{{ _p('auth::pages.token.close', 'Close') }}" store-data="showToken"
                      disabled-dialog>
            <fb-input name="plain_token" label="{{ _p('auth::pages.token.token', 'Token') }}" readonly></fb-input>
        </form-builder>
    </content-window>

    <content-window name="change_token" class="modal_formbuilder" title="{{  _p('auth::pages.token.are_you_sure_change', 'Are you sure change?') }}">
        <form-builder :edited="true" url="{{route('auth.token.change') }}/{id}" method="patch"
                      @sended="AWEMA.emit('content::tokens_table:update')"
                      send-text="{{ _p('auth::pages.token.confirm', 'Confirm') }}" store-data="changeToken"
                      disabled-dialog>
        </form-builder>
    </content-window>

    <content-window name="delete_token" class="modal_formbuilder" title="{{  _p('auth::pages.token.are_you_sure_delete', 'Are you sure delete?') }}">
        <form-builder :edited="true" url="{{route('auth.token.delete') }}/{id}" method="delete"
                      @sended="AWEMA.emit('content::tokens_table:update')"
                      send-text="{{ _p('auth::pages.token.confirm', 'Confirm') }}" store-data="deleteToken"
                      disabled-dialog>

        </form-builder>
    </content-window>
@endsection
