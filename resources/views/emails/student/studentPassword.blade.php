@component('mail::message')
# Welcome to MISCEO

This is your login password. This password was generated automatically.
Upon first login you would be promted to change it. Thanks for understanding.

passcode: {{$data}}
@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
