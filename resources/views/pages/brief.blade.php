<div class="block" style="background: {{ $location->color()}};">

	<h2>{{ $location->title}}</h2>

	<img src="media/{{ $location->image }}" width="320px"/>

	<p>	{{ $location->description }}</p>

	<a class="button" href="{{ action('LocationsController@show', $location->id) }}">Подробнее</a>

</div>		