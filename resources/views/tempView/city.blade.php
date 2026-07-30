<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">

 </head>
 <body>

    <h2>City</h2>
{{-- {{ Breadcrumbs::render('city', $city->country->continent, $city->country, $city) }} --}}
{{-- <h2>{{ $city->name }}</h2> --}}
{{ Breadcrumbs::render('city',$continent,$country,$city) }}
@dd(['continent'=>$continent->name,'country'=>$country->name,'city'=>$city->name])

{{-- {{ Breadcrumbs::render('city', $continent, $country, $city) }} --}}

</body>
</html>
