@php
$Parsedown = new \Parsedown();
@endphp

{!! $Parsedown->text($post->content) !!}