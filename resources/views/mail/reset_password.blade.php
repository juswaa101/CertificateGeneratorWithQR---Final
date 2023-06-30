@component('mail::message')

From: {{ $details['fromEmail'] }}

Subject: {{ $details['subject'] }}

Message: {{ $details['body'] }}

@component('mail::button', ['url' => $details['url']])
Reset Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent