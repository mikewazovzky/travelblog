@extends('layouts.app')

@section('header')
	<link rel="stylesheet" type="text/css" href="/css/news.css">
@stop

@section('content')

	<div class="container news">
		<h1> {{ $article->title }}</h1>
		
		<article>
			<div class="body">
				{{ $article->body }}
			<div>
		</article>	
		
		<hr>
		
		@if (!Auth::guest() && Auth::user()->isAdmin())
			<table>
				<tr>
					<td>@include('news.buttonEdit')</td>
					<td>@include('news.buttonDelete')</td>
				</tr>
			</table>
		@endif		
		
	</div>

@stop