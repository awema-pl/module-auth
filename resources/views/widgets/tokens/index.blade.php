<div class="cell-1-2 cell--dsm">
    <h4>{{ _p('auth::pages.widget.token.api_token', 'API Token') }}</h4>
    <div class="card">
        <div class="card-body">
            <p>{!! _p('auth::pages.widget.token.your_api_token_for_extension', 'Your API access token for the <a href=":url" target="_blank">Chrome extension</a>.', ['url'=>'https://chrome.google.com/webstore/detail/baselinker/icoaajbonigiplnigpoaoligacmhmlof']) !!}</p>

            <button type="submit" class="btn" @click="AWEMA.emit('modal::enter_password:open')">
                {{_p('auth::pages.widget.token.show_api_token', 'Show API token')}}
            </button>
        </div>
    </div>
</div>

<modal-window name="enter_password" class="modal_formbuilder" title="{{ _p('auth::pages.widget.token.show_api_token', 'Show API token') }}">
    <form-builder url="{{ route('widget.auth.token.show_api_token') }}"
                  send-text="{{ _p('auth::pages.widget.token.show', 'Show') }}">
        <fb-input name="password" type="password" label="{{ _p('auth::pages.widget.token.enter_login_password', 'Please enter your login password') }}"></fb-input>
    </form-builder>
</modal-window>

<modal-window name="show_api_token" class="modal_formbuilder" title="{{ _p('auth::pages.widget.token.your_api_token', 'Your API token')  }}">
    <form-builder :edited="true" url="/"
                  send-text="{{ _p('auth::pages.widget.token.copy_to_clipboard', 'Copy to clipboard') }}" store-data="showApiToken"
                  disabled-dialog
                    @send="(data)=>{AWEMA.utils.clipboard(data.api_token);AWEMA.notify({status:'success', message:'{{ _p('auth::pages.widget.token.copied_to_clipboard', 'Copied to the clipboard.') }}'})}">
        <fb-input class="mb-20" size-sm name="api_token"></fb-input>
    </form-builder>
</modal-window>