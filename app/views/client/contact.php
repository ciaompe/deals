{% extends 'includes/client/default.php' %}

{% block title %} Contact Us {% endblock %}

{% block header%}{% endblock %}

{% block content %}

	<section id="page" class="mBtm-50">
    <div class="row">
        <div class="col-sm-8 mTop-30">
            <div class="panel-body frameLR bg-white shadow mTop-0">

                <h3 style="margin-bottom: 1px;margin-top: 25px;" class="heading-hr-new heading_hr mTop-30">CONTACT US</h3>
                <p>Your email address will not be published. All fields are required</p>

                <!-- widget -->
                <div class="widget contact-widget">
                    <form class="contact-form clearfix" action="{{urlFor('contact')}}" method="post">
                        
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group{% if errors.has('name') %} has-error{% endif %}">
                                    <label class="control-label">Name</label>
                                    <input type="text" class="form-control" placeholder="Name" name="name" {% if request.post('name') %} value="{{request.post('name')}}"{% endif %}>
                                    {% if errors.has('name') %} 
										<span class="help-block">{{errors.first('name') }}</span>
									{% endif %}
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group{% if errors.has('email') %} has-error{% endif %}">
                                    <label class="control-label">Email</label>
                                    <input type="text" class="form-control" placeholder="Email" name="email" {% if request.post('email') %} value="{{request.post('email')}}"{% endif %}>
                                    {% if errors.has('email') %} 
										<span class="help-block">{{errors.first('email') }}</span>
									{% endif %}
                                </div>
                            </div>
                          
                        </div>
                        <!-- row -->

                        <div class="form-group{% if errors.has('subject') %} has-error{% endif %}">
                            <label class="control-label">Subject</label>
                            <input type="text" class="form-control" placeholder="Subject" name="subject" {% if request.post('subject') %} value="{{request.post('subject')}}"{% endif %}>
                            {% if errors.has('subject') %} 
								<span class="help-block">{{errors.first('subject') }}</span>
							{% endif %}
                        </div>

                       <div class="form-group{% if errors.has('message') %} has-error{% endif %}">
                            <label class="control-label" for="message">Your message</label>
                            <textarea rows="6" cols="88" id="message" name="message" class="form-control">{% if request.post('message') %} {{request.post('message')}}{% endif %}</textarea>
                            {% if errors.has('message') %} 
								<span class="help-block">{{errors.first('message') }}</span>
							{% endif %}
                       </div>

                       <div class="form-group">
							<div class="g-recaptcha" data-theme="light" data-sitekey="{{sitekey}}"></div>

							{% if capcha is not empty %} 
								{% for value in capcha%}
								<span class="help-block" style="color:#a94442">please verify your humanity</span>
								{% endfor %}
							{% endif %}
						</div>

						<input type="hidden" name="csrf_token" value="{{csrf_token}}">

                        <div class="form-group">
                            <input type="submit" class="btn btn-raised ripple-effect btn-danger btn-md" id="submit-contact" value="Send message">
                        </div>

                    </form>
                </div>
                <!-- punica-contact-widget -->
            </div>
            <!-- /inner wrap -->
        </div>
        <div class="col-sm-4 sidebar">
            <div class="inner-side shadow mTop-0">
                <!-- /widget -->
                <div class="widget">
                    
                    <h3 class="heading-hr-new heading_hr mTop-30">CONTACT INFO</h3>

                    <address>
			          {{main_config.sitename}},
			          <br>
			          {{main_config.contact.address}}
			          <br> <br>
			          Phone : {{main_config.contact.phone}}
			          <br>
			          Fax : {{main_config.contact.fax}}
			          <br>
			           Email : {{main_config.contact.email}}
			          <br>

			        </address>
                </div>

                <h3 style="margin-bottom:20px;" class="heading-hr-new heading_hr mTop-30">OUR LOCATION</h3>
                <div class="google-maps">
                    <iframe src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAFFjtc3PxI1rSFQhiw6pZkA7FknsY8Z68&q={{main_config.contact.address}}" allowfullscreen width="100%" height="300" frameborder="0" style="border:0">
                    </iframe>
                </div>

            </div>
        </div>
        <!-- /col 4 - sidebar -->
    </div>

</section>

{% endblock %}