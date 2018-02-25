{% extends 'includes/admin/default_two.php' %}

{% block title %} Account update {% endblock %}

{% block pageheader %}

	<div class="page-header page-header-green-sea">
	  <h1><i class="fa fa-sort"></i>Account</h1>
	</div>
	<!-- END PAGE HEADER -->

	<ol class="breadcrumb">
	  <li><a href="{{urlFor('admin_home')}}">Home</a></li>
	  <li class="active">Update</li>
	  <li><a href="{{urlFor('admin_account_password')}}">Change Password</a></li>
	</ol>

	<!-- END BREADCRUMB -->

{% endblock %}

{% block content %}

		<div class="row">
			<div class="col-md-6">

				<div class="widget">
					<div class="widget-content-white glossed padded">		

						<h3 class="form-title form-title-first"><i class="fa fa-terminal"></i>Update Account</h3>


					    <form class="update_form" action="{{urlFor('admin_account_update')}}" method="POST">
							
							<div class="form-group{% if errors.has('first_name') %} has-error{% endif %}">
								<label class="control-label" for="first_name">First Name: </label>
								<input type="text" class="form-control" name="first_name" placeholder="John Doe" value="{% if request.post('first_name') %}{{request.post('first_name')}}{%else%}{{admin.f_name}}{%endif%}">
								{% if errors.has('first_name') %} 
									<span class="help-block">{{errors.first('first_name') }}</span>
								{% endif %}
							</div>
							<div class="form-group{% if errors.has('last_name') %} has-error{% endif %}">
								<label class="control-label" for="last_name">Last Name: </label>
								<input type="text" class="form-control" name="last_name" placeholder="John Doe" value="{% if request.post('last_name') %}{{request.post('last_name')}}{%else%}{{admin.l_name}}{%endif%}">
								{% if errors.has('last_name') %} 
									<span class="help-block">{{errors.first('last_name') }}</span>
								{% endif %}
							</div>
							<div class="form-group{% if errors.has('email') %} has-error{% endif %}">
								<label class="control-label" for="email">Email: </label>
								<input type="text" class="form-control" name="email" placeholder="johndoe@example.com" value="{% if request.post('email') %}{{request.post('email')}}{%else%}{{admin.email}}{%endif%}">
								{% if errors.has('email') %} 
									<span class="help-block">{{errors.first('email') }}</span>
								{% endif %}
							</div>
							
							{% include 'admin/account/password.php' %}

							<input type="hidden" name="csrf_token" value="{{csrf_token}}">
							<button class="btn btn-primary" type="submit" id="open_model">Update</button>

						</form>
					</div>
				</div>

			</div>
			
		<div style="width:264px; float:left; margin-left:15px;">
			<div class="widget">
			  		<div class="widget-content-white glossed padded">
  						<h3 class="form-title form-title-first"><i class="fa fa-image"></i>Avatar</h3>
  						<div class="deals_source_image" id="crop-avatar">
  							<div class="source_logo avatar-view" title="Change profile picture">
                  <img src="{{admin.getAvatar(baseUrl, images, true)}}" alt="propic">
  							</div>
  							{% include 'admin/account/proPic.php' %}
  						</div>
  						  <small style="margin-top:17px; display:block">Image Size : <b>200 * 200</b></small>
  			  		</div>
	        </div>
		</div>
			
  </div>

{% endblock %}

{% block footer %}
  <script>

		$(function() {

			$('.update_form').formValidation({
		        framework: 'bootstrap',
		        icon: {
		            valid: 'null',
		            invalid: 'null',
		            validating: 'null'
		        },
		        fields: {
	
					first_name: {
		                validators: {
		                    notEmpty: {
		                        message: 'First Name is required'
		                    }
		                   
		                }
		            },
		            last_name: {
		                validators: {
		                    notEmpty: {
		                        message: 'Last Name is required'
		                    }
		                   
		                }
		            },
		            email: {
		                validators: {
		                    notEmpty: {
		                        message: 'Email address is required'
		                    },
		                    emailAddress: {
		                        message: 'Enter valid email address'
		                    }
		                }
		            },
		            password: {
		            	validators: {
		                   notEmpty: {
		                        message: 'Current Password is required'
		                    }, 
		                   
		                }
		            }

		        }
    		}).on('success.form.fv', function(e) {

	            e.preventDefault();
	            var $form = $(e.target),
	            	$fv = $(e.target).data('formValidation'),
	            	$button = $form.data('formValidation').getSubmitButton();

	            $('#password-modal').modal('hide');

	            switch ($button.attr('id')) {
	                case 'open_model':
	                	$('#password-modal').modal('show');
	                    break;
	                case 'update_form':
	                	$fv.defaultSubmit();
	                    break;
	            }
	            
	       	});


		$(".select-image").click(function() {
			  $("#avatarInput").click();
		});

		return new CropAvatar($('#crop-avatar'));

		});
	</script>

	<script type="text/javascript">
		$(function() {
			$('.update_form').on('keyup keypress', function(e) {
			  var code = e.keyCode || e.which;
			  if (code == 13) { 
			    e.preventDefault();
			    return false;
			  }
			});
		});
	</script>

{% endblock %}