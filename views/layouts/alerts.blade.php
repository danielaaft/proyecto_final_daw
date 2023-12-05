@if(isset($_SESSION['alert']))
	@foreach($_SESSION['alert'] as $alert)
		<div class="alert alert-{{$alert['type']}} alert-dismissible" role="alert" style="margin-top:20px">
			@if($alert['title'] != '')
				<strong>{{$alert['title']}}</strong> 
			@endif
			<?php echo html_entity_decode($alert['text']) ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>	
	@endforeach
@endif
<?php unset($_SESSION['alert']); ?>