<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet" />
    <script src="{{ mix('/js/app.js') }}" defer></script>

    <link href="{{ asset('/css/vue-multiselect.css') }}" rel="stylesheet">
    
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('googleAPI.key')}}&libraries=places&callback=Function.prototype"></script>

    {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
    @inertiaHead
  </head>
  <body>
    @inertia
  </body>
</html>
