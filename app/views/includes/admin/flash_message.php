{% if flash.error %}
	<div class="widget error-widget">
		<div class="alert alert-danger">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			 <strong>Oh snap! </strong>{{ flash.error }}
		</div>
	</div>
{% endif %}

{% if flash.success %}
	<div class="widget success-widget">
		<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			<strong>Success! </strong>{{ flash.success }}
		</div>
	</div>
{% endif %}