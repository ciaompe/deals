{% extends 'includes/client/default.php' %}

{% block title %}Update Profile{% endblock %}

{% block header %}

{% if auth.isLocalUser() %}
  <link rel="stylesheet" type="text/css" href="{{css}}/framework/crop/cropper.min.css">
  <link rel="stylesheet" type="text/css" href="{{css}}/framework/crop/crop.css">
{% endif %}

{% endblock %}

{% block content %}

  <section id="page">

   <div class="row">
      <!-- Main Col -->
      <div class="main-col col-md-9">

          <div class="panel-body frameLR bg-white shadow mTop-30 mBtm-50">
              <!-- Nav tabs -->
              <ul class="nav nav-tabs mTop-20" role="tablist">

                  <li class="active" role="presentation">
                      <a aria-controls="home" data-toggle="tab" href="#watch" role="tab">Watch List</a>
                  </li>

                  <li role="presentation">
                      <a aria-controls="profile" data-toggle="tab" href="#profile" role="tab">Profile</a>
                  </li>

                  {% if auth.isLocalUser() %}
                  <li role="presentation">
                      <a aria-controls="profile" data-toggle="tab" href="#password" role="tab">Change Password</a>
                  </li>
                  {%endif%}

              </ul>
              <!-- Tab panes -->

              <div class="tab-content">

                  <div class="tab-pane active" id="watch" role="tabpanel">
                      <!-- table Contents -->
                      {% include 'client/auth/account/includes/watchlist.php' %}
                      <!-- /table Contents -->
                  </div>

                  <div class="tab-pane" id="profile" role="tabpanel">

                      <div>
                        <h3>Profile Update</h3>
                        <hr style="margin-bottom:25px;">
                      </div>
                      
                      <form class="update_form" action="{{urlFor('account_update')}}" method="POST">

                        <div class="form-group{% if errors.has('first_name') %} has-error{% endif %}">
                        <label class="control-label" for="first_name">First Name: </label>
                        <input type="text" class="form-control" name="first_name" placeholder="John Doe" value="{% if request.post('first_name') %}{{request.post('first_name')}}{%else%}{{auth.f_name}}{%endif%}">
                        {% if errors.has('first_name') %} 
                          <span class="help-block">{{errors.first('first_name') }}</span>
                        {% endif %}
                      </div>

                      <div class="form-group{% if errors.has('last_name') %} has-error{% endif %}">
                        <label class="control-label" for="last_name">Last Name: </label>
                        <input type="text" class="form-control" name="last_name" placeholder="John Doe" value="{% if request.post('last_name') %}{{request.post('last_name')}}{%else%}{{auth.l_name}}{%endif%}">
                        {% if errors.has('last_name') %} 
                          <span class="help-block">{{errors.first('last_name') }}</span>
                        {% endif %}
                      </div>

                      <div class="form-group{% if errors.has('email') %} has-error{% endif %}">
                        <label class="control-label" for="email">Email: </label>
                        <input type="text" class="form-control" name="email" placeholder="johndoe@example.com" value="{% if request.post('email') %}{{request.post('email')}}{%else%}{{auth.email}}{%endif%}">
                        {% if errors.has('email') %} 
                          <span class="help-block">{{errors.first('email') }}</span>
                        {% endif %}
                      </div>
                      
                      {%if(auth.isLocalUser())%}
                        {% include 'client/auth/account/includes/passwordModal.php' %}
                      {%endif%}

                      <input type="hidden" name="csrf_token" value="{{csrf_token}}">

                      <div class="form-group">
                        <button class="btn btn-success" type="submit" id="open_model_profile">UPDATE</button>
                      </div>

                     </form>

                  </div>

                 {% if auth.isLocalUser() %}
                  <div class="tab-pane" id="password" role="tabpanel">

                      <div>
                        <h3>Change Password</h3>
                        <hr style="margin-bottom:25px;">
                      </div>
                      
                      <form class="password_update_form" action="{{urlFor('account_password')}}" method="POST">

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

                      <div class="form-group{% if errors.has('password') %} has-error{% endif %}">
                        <label class="control-label" for="password">Current Password: </label>
                        <input type="password" class="form-control" name="password">
                        {% if errors.has('password') %} 
                          <span class="help-block">{{errors.first('password') }}</span>
                        {% endif %}
                      </div>
                                  
                      <input type="hidden" name="csrf_token" value="{{csrf_token}}">
                      

                      <div class="form-group">
                        <button class="btn btn-success" type="submit">UPDATE</button>
                      </div>

                     </form>

                  </div>
                {% endif%}


              </div>

          </div>
      </div>
      <!-- /Main Col -->

      <!-- Side Col -->
      <div class="side-col col-md-3">
         {% include 'client/auth/account/includes/sidebar.php' %}
      </div>

      <!-- /Side Col -->

   </div>

</section>


{% endblock %}


{% block footer %}
  
  <script type="text/javascript" src="{{js}}/client/jquery.blockUI.js"></script>

  <script>

    $(function() {

      var isLocal = {%if(auth.isLocalUser())%} {{1}} {%else%} {{0}} {%endif%};

      $('.update_form')
          .formValidation({
            framework: 'bootstrap',
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
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
                        },
                        remote: {
                            message: 'Email address is already taken',
                            url: "{{ urlFor('auth_update_user_email_check') }}",
                            type: 'POST',
                            data: {'email':$('input[name=email]').val(), 'csrf_token': $('input[name=csrf_token]').val()},
                        }

                    }
                },
                password: {
                  validators: {
                    notEmpty: {
                            message: 'Current password is required'
                        },
                        callback: {
                              callback: function(value, validator, $field) {
                                  return (isLocal == 1) ? true : false;
                              }
                          }
                       
                    }
                }

            }
        })
        .on('err.field.fv', function(e, data) {
            data.fv.disableSubmitButtons(false);
        })
        .on('success.field.fv', function(e, data) {
            data.fv.disableSubmitButtons(false);

             var $parent = data.element.parents('.form-group');

            // Remove the has-success class
            $parent.removeClass('has-success');

            // Hide the success icon
            data.element.data('fv.icon').hide();
        })

        .on('success.form.fv', function(e) {
              if(isLocal == 1){

              e.preventDefault();

               var $form = $(e.target),
                $fv = $(e.target).data('formValidation');
                $button = $form.data('formValidation').getSubmitButton(),
                $('#password-modal').modal('hide');

                switch ($button.attr('id')) {
                    case 'open_model_profile':
                        checkisLocals(isLocal, $fv);
                        break;
                    case 'update_form':

                      $.ajax({
                          method: "POST",
                          url: "{{urlFor('account_update')}}",
                          data: $form.serialize(),
                          beforeSend: function() {
                               $.blockUI({ message: '<div id="blockUi"><img src="{{images}}/blokui_loader.GIF" alt=""></div>' });
                          },
                          success: function(data) {
                            obj = JSON.parse(data);
                            if (obj.status == 200) {
                                $('#password-modal').modal('hide');
                                $('#currentPassword').val('');
                                $.unblockUI();
                                swal("Success !", obj.msg, "success");
                            } 
                            if(obj.status == 400) {
                                $('#password-modal').modal('hide');
                                $('#currentPassword').val('');
                                $.unblockUI();
                                swal("Error !", obj.msg, "error");
                            }
                          }
                      });
                   
                      break;
                }
              }
                          
        });

        if(isLocal == 0){
          $('#open_model_profile').on('click', function(e) {
              e.preventDefault();
              $.ajax({
                method: "POST",
                url: "{{urlFor('account_update')}}",
                data: $('.update_form').serialize(),
                beforeSend: function() {
                     $.blockUI({ message: '<div id="blockUi"><img src="{{images}}/blokui_loader.GIF" alt=""></div>' });
                },
                success: function(data) {
                  obj = JSON.parse(data);
                  if (obj.status == 200) {
                      $.unblockUI();
                      swal("Success !", obj.msg, "success");
                  }
                }
              });
          });
        }

        $('.password_update_form').formValidation({
            framework: 'bootstrap',
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
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
        })
        .on('err.field.fv', function(e, data) {
            data.fv.disableSubmitButtons(false);
        })
        .on('success.field.fv', function(e, data) {
            data.fv.disableSubmitButtons(false);

             var $parent = data.element.parents('.form-group');

            // Remove the has-success class
            $parent.removeClass('has-success');

            // Hide the success icon
            data.element.data('fv.icon').hide();
        })
        .on('success.form.fv', function(e) {

          e.preventDefault();
          var $form = $(e.target);

          $.ajax({
              method: "POST",
              url: "{{urlFor('account_password')}}",
              data: $form.serialize(),
              beforeSend: function() {
                   $.blockUI({ message: '<div id="blockUi"><img src="{{images}}/blokui_loader.GIF" alt=""></div>' });
              },
              success: function(data) {
                 obj = JSON.parse(data);
                  if(obj.status == 400) {
                    $.unblockUI();
                    swal("Error !", obj.msg, "error");
                  } else {
                    $.unblockUI();
                     swal("Success !", 'Password changed successfully', "success");
                  }

              }
          });

        });

        $('.delete').click(function(e) {
              e.preventDefault();
              var url = $(this).attr('href');
              swal({   
                  title: "Are you sure?",
                  text: "want to delete this deal from your Watch list ?",   
                  type: "warning",   
                  showCancelButton: true,   
                  confirmButtonColor: "#DD6B55",   
                  confirmButtonText: "Yes, delete it!",   
                  cancelButtonText: "No, cancel !",   
                  closeOnConfirm: true,   
                  closeOnCancel: true
              },
              function(isConfirm){   
                  if (isConfirm) { 
                      window.location.href = url;
                  } 
              });

      });

    });

    function checkisLocals(isLocal, $fv) {
          
          if (isLocal == 1) {
            $('#password-modal').modal('show');
            $('#currentPassword').val('');
          } 

          if(isLocal == 0) {
            $('#password-modal').modal('hide');
            $fv.defaultSubmit();
          }
      }

  </script>

  {% if auth.isLocalUser() %}

    <script src="{{js}}/framework/crop/cropper.min.js"></script>
    <script src="{{js}}/framework/crop/crop.js"></script>

    <script type="text/javascript">
      $(function() {

        $('.update_form').on('keyup keypress', function(e) {
          var code = e.keyCode || e.which;
          if (code == 13) { 
            e.preventDefault();
            return false;
          }
        });

        $(".select-image").click(function() {
           $("#avatarInput").click();
        });

        return new CropAvatar($('#crop-avatar'));

      });
    
    </script>

 {% endif %}

{% endblock %}