@component('mail::message')
# {{ $birthday->name }} has birthday {{ $reminder->getDiffForHumans() }}.

This is a birthday reminder set in birthday app.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
