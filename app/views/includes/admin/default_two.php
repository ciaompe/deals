<!DOCTYPE html>
<html lang="">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Deal Dashboard | {% block title %} {% endblock %}</title>

	{% include 'includes/admin/css.php' %}	

	<!-- Block header for pages -->
	{% block header %}{% endblock %} 

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

</head>
<body>

	<!--[if lt IE 7]>
        <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <div class="all-wrapper">
		<div class="row">

			<div class="col-md-3">
				{% include 'includes/admin/nav.php' %}
			</div>

			<div class="col-md-9">
				<div class="content-wrapper">
		        	<div class="content-inner content-inner-white">
		        			
		        			{% block pageheader %}{% endblock %}

							<div class="main-content">
								{% include 'includes/admin/flash_message.php' %}
								{% block content %}
										
								{% endblock %}
							</div>

						</div>	
		        	</div>
		        </div>
		    </div>

		</div>
	</div>

	{% include 'includes/admin/js.php' %}	


	<script>
	  	$('.success-widget').delay(2000).slideUp(300);
	</script>

	{% block footer %}{% endblock %}
	
</body>
</html>