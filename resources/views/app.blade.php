<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.2.0/css/line.css">
      @vite(['resources/css/app.css', 'resources/js/app.js'])
    @inertiaHead
  </head>
  <body>
    @inertia
   <script src="https://cdn.jsdelivr.net/npm/pikaday/pikaday.js"></script>
   {{-- <script type="module" src="https://unpkg.com/cally"></script> --}}

<script>
  // var picker = new Pikaday({ field: document.getElementById('myDatepicker') });
</script>
  </body>
</html>