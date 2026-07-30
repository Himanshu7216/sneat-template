<!DOCTYPE html>
<html>
<head>

{{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous"> --}}
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
 </head>
 <body>
<h2>Home</h2>
{{ Breadcrumbs::render('home') }}


{{-- {{ Breadcrumbs::render('country','Africa','South Africa') }} --}}
{{-- {{ Breadcrumbs::render('home') }}
{{ Breadcrumbs::render('continent') }} --}}
{{-- @dd($continent) --}}




</body>
</html>
