{% extends 'includes/admin/default.php' %}

{% block title %} Login {% endblock %}

{% block content %}

	<div class="content-wrapper bold-shadow">
	    <div class="content-inner">
	       <div class="main-content main-content-grey-gradient no-page-header">
	         <div class="main-content-inner">

			    <form action="{{urlFor('admin_login')}}" method="POST">

					<div class="form-group{% if errors.has('email') %} has-error{% endif %}">
						<label class="control-label" for="email">Email: </label>
						<input type="text" class="form-control" name="email" value="test@gmail.com">
						{% if errors.has('email') %} 
							<span class="help-block">{{errors.first('email') }}</span>
						{% endif %}
					</div>
										
					<div class="form-group{% if errors.has('password') %} has-error{% endif %}">
						<label class="control-label" for="password">Password: </label>
						<input  type="password" class="form-control" name="password" value="test123456">
						{% if errors.has('password') %} 
							<span class="help-block">{{errors.first('password') }}</span>
						{% endif %}
					</div>

					{% if showCapcha %}
						<!-- Google Recapcha -->
						<div class="form-group">
							<div class="g-recaptcha" data-theme="light" data-sitekey="{{sitekey}}"></div>

							{% if capcha is not empty %} 
								{% for value in capcha%}
								<span class="help-block" style="color:#b94a48">please verify your humanity</span>
								{% endfor %}
							{% endif %}
						</div>
					<!-- End Capcha -->
					{% endif %}

					<input type="hidden" name="csrf_token" value="{{csrf_token}}">

					<button type="submit" class="btn btn-primary btn-md">Sign in</button>
	            	<a href="{{urlFor('admin_forgot')}}" class="btn btn-link">Forogot Password ?</a>

				</form>
			
          </div>
        </div>
      </div>
    </div>

{% endblock %}

{% block footer %}
	<script src='https://www.google.com/recaptcha/api.js'></script>
{% endblock %}