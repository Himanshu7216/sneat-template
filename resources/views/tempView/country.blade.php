<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">

 </head>
 <body>
<h2>Country</h2>
{{-- {{ Breadcrumbs::render('country', $country->continent, $country) }} --}}
{{-- <h2>{!! Breadcrumbs::render('homes') !!}</h2> --}}
{{ Breadcrumbs::render('country',$continent,$country) }}
@dd(["continent : ".$continent->name,"country : ".$country->name])

{{-- {{ Breadcrumbs::render('country', $continent, $country) }} --}}
</body>
</html>
