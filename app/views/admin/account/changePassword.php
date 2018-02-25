{% extends 'includes/admin/default_two.php' %}

{% block title %} Change Password {% endblock %}

{% block pageheader %}

	<div class="page-header page-header-green-sea">
	  <h1><i class="fa fa-sort"></i>Account</h1>
	</div>
	<!-- END PAGE HEADER -->

	<ol class="breadcrumb">
	  <li><a href="{{urlFor('admin_home')}}">Home</a></li>
	  <li><a href="{{urlFor('admin_account_update')}}">Update</a></li>
	  <li class="active">Change Password</li>
	</ol>

	<!-- END BREADCRUMB -->

{% endblock %}

{% block content %}

		<div class="row">
			<div class="col-md-6">

				<div class="widget">
					<div class="widget-content-white glossed padded">

						 <h3 class="form-title form-title-first"><i class="fa fa-terminal"></i>Change Password</h3>
	

					    <form class="update_form" action="{{urlFor('admin_account_password')}}" method="POST">

					   		<div class="form-group{% if errors.has('password_new') %} has-error{% endif %}">
								<label class="control-label" for="password">New Password: </label>
								<input  type="password" class="form-control" name="password_new">
								{% if errors.has('password_new') %} 
									<span class="help-block">{{errors.first('password_new') }}</span>
								{% endif %}
							</div>

							<div class="form-group{% if errors.has('password_confirm') %} has-error{% endif %}">
								<label class="control-label" for="password_confirm">Confirm Password: </label>
								<input  type="password" class="form-control" name="password_confirm">
								{% if errors.has('password_confirm') %} 
									<span class="help-block">{{errors.first('password_confirm') }}</span>
								{% endif %}
							</div>
							
							{% include 'admin/account/password.php' %}

							<input type="hidden" name="csrf_token" value="{{csrf_token}}">
							<button class="btn btn-primary" type="submit" id="open_model">Update</button>

					   	</form>

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
	
					password_new: {
		                validators: {
		                    notEmpty: {
		                        message: 'New password is required'
		                    },
		                    stringLength: {
		               			min: 6,
		                        message: 'Password must be greater than 6 characters',
		                    }
		                   
		                }
		            },
		            password_confirm: {
		                validators: {
		                    notEmpty: {
		                        message: 'Confirm Password is required'
		                    },
		                    identical: {
			                   field: 'password_new',
			                   message: 'The new password and its confirm are not the same'
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