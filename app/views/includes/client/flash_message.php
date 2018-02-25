{% if flash.error %}
	<div class="alert alert-danger">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		{{flash.error}}
	</div>
{% endif %}

{% if flash.success %}
	<div class="alert alert-success">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		{{flash.success}}
	</div>
{% endif %}

{% if auth %}

	{% if auth.activate == false %}
		<div class="alert alert-danger">
			Please Activate your account otherwise you cannot do anyting in here, if you lost the activate code, please <a href="{{urlFor('auth_resend')}}">Resend</a> the code again
		</div>
	{% endif %}

{% endif %}