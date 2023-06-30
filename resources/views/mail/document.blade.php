@component('mail::message')

From: {{ $details['fromEmail'] }}

Message: {{ $details['body'] }}

Name of Seminar/Training  : {{ $details['training'] }}

Participant's Name : {{ $details['name'] }}

Certificate ID: {{ $details['id'] }}

@component('mail::button', ['url' => $details['url']])
Download your Certificate Here
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent