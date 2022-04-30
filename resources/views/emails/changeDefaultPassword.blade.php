@component('mail::message')
This verification code is for setting up a new
password for your account

Use this code for verifcation.
{{$data}}
@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
