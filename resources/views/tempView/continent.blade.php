<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">

 </head>
 <body>

    <h2>Continent</h2>

    {{ Breadcrumbs::render('continent',$continent) }}
    @dd(["continent : ".$continent->name])

{{-- <h2>{{ $continent->name }}</h2> --}}

{{-- {{ Breadcrumbs::render('continent', $continent) }} --}}
{{-- {{ Breadcrumbs::render('continent',$continent) }} --}}


{{-- @dd($continent) --}}

</body>
</html>
