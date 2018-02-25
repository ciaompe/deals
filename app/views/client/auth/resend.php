{% extends 'includes/client/default.php' %}

{% block title %} Resend activation code {% endblock %}

{% block header %}{% endblock %}

{% block content %}
	
	 <div class="omb_login">

    	<h3 class="omb_authTitle">Resend Activation Code ?</h3>
	
		<div class="row omb_row-sm-offset-3">
			<div class="col-xs-12 col-sm-6">	

			    <form class="omb_loginForm" action="{{urlFor('auth_resend')}}" method="POST">

					<div class="form-group{% if errors.has('email') %} has-error{% endif %}">
						<label class="control-label" for="email">Email: </label>
						<input type="email" class="form-control" name="email">
						{% if errors.has('email') %} 
							<span class="help-block">{{errors.first('email') }}</span>
						{% endif %}
					</div>

					<input type="hidden" name="csrf_token" value="{{csrf_token}}">

					<button class="btn btn-lg btn-primary btn-block" type="submit">Send</button>

				</form>
			</div>
    	</div>   	
	</div>
	<br><br>
{% endblock %}