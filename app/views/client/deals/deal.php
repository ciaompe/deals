{% extends 'includes/client/default.php' %}

{% block title %} {{deal.title}} {% endblock %}

{% block header %}

<!-- START - Facebook Open Graph, Google+ and Twitter Card Tags 1.6.3 -->
<meta property="og:site_name" content="{{main_config.sitename}}"/>
<meta property="og:title" content="{{deal.title}}"/>
<meta itemprop="name" content="{{deal.title}}"/>
<meta property="og:description" content="{{deal.limiText(deal.discriptionText(), 300)}}"/>
<meta property="og:url" content="{{constant('APP_HOST')}}{{urlFor('deal_single_page', {'slug' : deal.dealUrl(), 'id' : deal.id} )}}"/>
<meta property="og:type" content="article"/>
<!-- END - Facebook Open Graph, Google+ and Twitter Card Tags -->

<meta property="og:image" content="{{deal.getDealImage(baseUrl, images, 'medium', true)}}"/>
<meta property="og:image:width" content="{{config.deal.medium.width}}" />
<meta property="og:image:height" content="{{config.deal.medium.height}}" />
<meta property="og:locale" content="en_us"/>

<!-- Twitter card meta tags -->
<meta name="twitter:card" content="{{deal.getDealImage(baseUrl, images, 'large', true)}}">
<meta name="twitter:site" content="{{main_config.sitename}}">
<meta name="twitter:title" content="{{deal.title}}">
<meta name="twitter:description" content="{{deal.limiText(deal.discriptionText(), 300)}}"/>
<meta name="twitter:image:src" content="{{deal.getDealImage(baseUrl, images, 'large', true)}}">


<!-- Google plus and Pinterest meta tags -->
<meta itemprop="name" content="{{deal.title}}">
<meta itemprop="description" content="{{deal.limiText(deal.discriptionText(), 300)}}">
<meta itemprop="image" content="{{deal.getDealImage(baseUrl, images, 'medium', true)}}">


{% endblock %}

{% block content %}

<!-- INNER PAGE -->
<section id="inner-page">
	
	<div class="row">

		<div class="col-sm-8">
			<!-- POST MEDIA -->
			<div class="post-media clearfix">
				<!-- FLEXSLIDER -->
                <div class="flexslider" id="slider">
                	<!-- SLIDES -->
                    <ul class="slides">
                    	{% if deal.getDealImages(baseUrl, images, true) is iterable %}
                    		{% for image in deal.getDealImages(baseUrl, images, true) %}
		                        <li>
		                            <img alt="" class="img-responsive" src="{{image}}.jpg">
		                        </li>
                        	{% endfor %}
                        {% else %}
                        	<li>
                        		<img alt="" class="img-responsive" src="{{images}}/deal_default_large.png">
                        	</li>
                        {% endif %}
                    </ul>
                    <!-- END SLIDES -->
                </div>
                <!--/slider -->
				{% if deal.getDealImages(baseUrl, images, true) is iterable %}
                <div class="flexslider" id="carousel">
                    <ul class="slides">
                    	{% for image in deal.getDealImages(baseUrl, images, true) %}
                        <li class="deal_slider_thubmnail"><img alt="" src="{{image}}_thumbnail.jpg"></li>
                        {% endfor %}
                    </ul>
                </div>
                {% endif %}
                <!--/.carousel sinc -->

            </div>
            <!--/.post media -->
			
			<!-- Deal Updated at -->
            <div class="smallFrame shadow bg-white">
                <i class="ti-calendar"></i>
                <div class="content">
                 {% if deal.updated_at is not null %}  
                   This Deal was updated on <b>{{deal.updatedAt()}}</b>
                 {% else %}
                 	This Deal was created at <b>{{deal.createdAt()}}</b>
                 {% endif %}

                </div>
            </div>
            <!-- END Deal updated at -->

            <!-- DESCRIPTION AND COMMENTS TAB -->

            <div class="tabbable responsive widget-inner shadow bg-white mTop-20" id="tabs" role="tabpanel">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="active" role="presentation">
                        <a aria-controls="home" data-toggle="tab" href="#overview" role="tab">Overview</a>
                    </li>
                    <li role="presentation">
                        <a aria-controls="reviews" id="review-tab-but" data-toggle="tab" href="#reviews" role="tab">reviews</a>
                    </li>
                </ul><!-- Tab panes -->

                <div class="tab-content deal-single-page-tab-content">
                    <div class="tab-pane active" id="home" role="tabpanel">
                        <div class="tab-content">

                            <div class="tab-pane fade active in" id="overview">
                                <!-- Description goes here -->
                                {{deal.getDiscription() | raw}}
                               
                            </div><!-- /tab content -->

                            <div class="tab-pane" id="reviews" role="tabpanel">
                                <section class="tab-content">
                            		
                            		{% include 'client/deals/includes/commentForm.php' %}

                                </section>

                            </div>
                        </div>
                    </div><!--/tabs -->
                </div><!-- /inner widget -->
            </div>

            <!-- END TAB -->
            {% if showAd(728, 90, baseUrl, images) is not null%}
			<br>
             <div class="row show-ad">
		        <div class="col-sm-12">
		        	<div class="widget-inner bg-white shadow">
		          		{{showAd(728, 90, baseUrl, images)|raw}}
		          	</div>
		        </div>
		    </div><!--/.row -->
		    {% endif %}

		</div>
		<!-- COL-8 -->

		<!-- COL-4s -->
		<div class="col-sm-4">

			<div class="widget-inner bg-white shadow">
				<div class="animated fadeIn bg-white Aligner text-center">

					<div class="content">
                        <div class="deal-content">
                            <h3>{{deal.title}}</h3>
                        </div>
                        <div class="price">
                            <h1>${{deal.getDiscount() |number_format(2)}}</h1>
                        </div>
                        <div class="buy-now mBtm-30 mTop-20">
                            <a href="{{urlFor('deal_url_redirect', {'id': deal.id})}}" target="_blank" class="btn btn-success btn-lg btn-raised ripple-effect btn-block">
                                <i class="ti-shopping-cart"></i>
                                Get Deal
                            </a>
                        </div>
                        <div class="dealAttributes">
                            <div class="valueInfo bg-light">
                                <div class="value">
                                    <p class="value">${{deal.price |number_format(2)}}</p>
                                    <p class="text">Value</p>
                                </div>
                                <div class="discount">
                                    <p class="value">{{deal.discount}}%</p>
                                    <p class="text">Discount</p>
                                </div>
                                <div class="save">
                                    <p class="value">${{deal.price - deal.getDiscount() |number_format(2)}}</p>
                                    <p class="text">SAVINGS</p>
                                </div>
                            </div>
                            <div class="timeLeft text-center">
                                <p>Hurry up Only afew deals left</p>
                                {% if deal.type == 'count' %}
                                    <span class="time">
                                        <i class="ti-timer color-green"></i>
                                        <span class="deal-countdown" data-countdownt="{{deal.getCountDown()}}"></span>
                                    </span>
                                {% endif %}
                            </div>

                            <ul class="statistic list-unstyled list-inline">
                                <li><p><i class="ti-thumb-up"></i><b><span class="likecount" data-num="{{deal.likesCount()}}"> {{deal.likesCount()}}</span> </b> Likes</p></li>
                                <li><p><i class="ti-thumb-down"></i><b><span class="dislikecount" data-num="{{deal.dislikesCount()}}"> {{deal.dislikesCount()}}</span> </b> Dislikes</p></li>
                                <li><p><i class="ti-comment"></i><b><span> {{deal.commentCount()}}</span> </b> Comments</p></li>
                            </ul>

                            <div class="social-sharing text-center" data-permalink="{{constant('APP_HOST')}}{{ urlFor('deal_single_page', {'slug' : deal.dealUrl(), 'id' : deal.id} ) }}">
	                            <!-- https://developers.facebook.com/docs/plugins/share-button/ -->
	                                <a class="share-facebook" href="http://www.facebook.com/sharer.php?u={{constant('APP_HOST')}}{{ urlFor('deal_single_page', {'slug' : deal.dealUrl(), 'id' : deal.id} ) }}" target="_blank">
	                                    <span class="icon icon-facebook"></span>
	                                    <span class="share-title">Share</span>
	                                </a> 
	                                <!-- https://dev.twitter.com/docs/intents -->
	                                <a class="share-twitter" href="http://twitter.com/share?url={{constant('APP_HOST')}}{{ urlFor('deal_single_page', {'slug' : deal.dealUrl(), 'id' : deal.id} ) }}" target="_blank">
	                                    <span class="icon icon-twitter"></span>
	                                    <span class="share-title">Tweet</span>
	                                </a>

	                                <a target="_blank" href="http://plus.google.com/share?url={{constant('APP_HOST')}}{{ urlFor('deal_single_page', {'slug' : deal.dealUrl(), 'id' : deal.id} ) }}" class="share-google">
								      <!-- Cannot get Google+ share count with JS yet -->
								      <span class="icon icon-google" aria-hidden="true"></span>
								      <span class="share-title">Google+</span>
								    </a>

	                         </div>
                        </div>
                    </div>

				</div>
				<!-- END ANIMATED CONTENT -->
			</div>
			<!-- END WIDGET -->

			<!-- LIKE AND DISLIKE BUTTONS -->
			<div class="smallFrame shadow bg-white">
                <div class="row">

                    <div class="col-lg-3">
                        <button  id="like" type="button" class="rate btn{% if deal.liked(auth.id) %} btn-success {% else %} btn-default {% endif %} btn-raised ripple-effect btn-block"  data-html="true"  data-container="body" data-toggle="popover" data-placement="bottom" data-content="<a href='{{urlFor('auth_login')}}?r={{ urlFor('deal_single_page', {'slug' : deal.dealUrl(), 'id' : deal.id} ) }}'>Sign in</a> to make your deal rate" title="Like this deal ?">
                       		<span class="ti-thumb-up"></span>  Like
                        </button>
                    </div>

                    <div class="col-lg-3">
                        <button id="dislike" type="button" class="rate btn{% if deal.disLiked(auth.id) %} btn-danger {% else %} btn-default {% endif %} btn-raised ripple-effect btn-block"  data-html="true"  data-container="body" data-toggle="popover" data-placement="bottom" data-content="<a href='{{urlFor('auth_login')}}?r={{ urlFor('deal_single_page', {'slug' : deal.dealUrl(), 'id' : deal.id} ) }}'>Sign in</a> to make your deal rate" title="Dislike this deal ?">
                        	<span class="ti-thumb-down"></span> Nope
                        </button>
                    </div>

                    <div class="col-lg-6">
                    	{% if auth.inList(deal.id) %}
                        <a href="{{urlFor('deal_remove_list', {'id' : deal.id})}}" id="removeDeal" class="btn btn-primary btn-raised ripple-effect btn-block">
                        	<span class="ti-trash"></span> in Watchlist
                        </a>
                    	{% else %}
                    	 <a href="{{urlFor('deal_add_list', {'id' : deal.id})}}" class="btn btn-danger btn-raised ripple-effect btn-block">
                        	<span class="ti-plus"></span> Add to Watchlist
                        </a>
                        {% endif %}
                    </div>

                    <input type="hidden" name="csrf_token" value="{{csrf_token}}">

                </div>
            </div>
            <!-- END LIKE BUTTONS -->

            
			<!-- Inlcude sidebar items -->
			{% include 'client/deals/includes/sidebar.php' %}


		</div>
		<!-- END COL-4 -->

	</div>
	<!-- END ROW -->


</section>
<!-- END INNER PAGE -->


{% endblock %}

{% block footer %}

<script type="text/javascript">

	$.urlParam = function(name){
		var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
		return results;
	};

	if($.urlParam('page')) {
		$('#review-tab-but').tab('show');
		$('html, body').animate({ 
		  scrollTop: $('#reviews').offset().top-600
		}, '1000');
	}

	$(function() {

		$('.delete-comment').click(function(e) {
	        e.preventDefault();
	        var url = $(this).attr('href');
	        swal({   
	            title: "Are you sure?",
	            text: "want to delete this comment ?",   
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

	   	$('.edit-btn').on('click', function(e) {
		
			var thisbtn = this;

			e.preventDefault();

			$('#comment-modal').modal('toggle');
			$('#editComment').val($(this).data('body'));
		
			$('#comment_update_form')
				.formValidation('destroy')
				.formValidation({
			        framework: 'bootstrap',
			        icon: {
			            valid: 'null',
			            invalid: 'null',
			            validating: 'null'
			        },
			        fields: {
						comment: {
			                validators: {
			                    notEmpty: {
			                        message: 'Comment is required'
			                    },
			                    stringLength: {
			               			max: 250,
			                        message: 'Comment must be less than 250 characters',
			                    }
			                   
			                }
			            }
			        }

				}).on('success.form.fv', function(e) {
			        e.preventDefault();

			        $.ajax({
				        type: "POST",
				        data: { csrf_token: $('input[name="csrf_token"]').val(), id: $(thisbtn).data('id'), comment: $('#editComment').val()},
				        url: "{{urlFor('deal_edit_comment')}}",
				        success: function(data) {
				        	obj = jQuery.parseJSON(data);
					        if (obj.status == 200) {
					        	$('.comment-wrapper-'+$(thisbtn).data('id')).html($('#editComment').val());
					        	$(thisbtn).data('body', $('#editComment').val());
					        	$('#comment-modal').modal('hide');
					        }
				        },
				        error: function() {
				        	console.log('Server Error occured');
				        }
					});

			});
		});

		var isSpam = {%if spam %} {{1}} {%else%} {{0}} {%endif%};

		if(isSpam == 1) {
			$('#review-tab-but').tab('show');
			  $('html, body').animate({ 
                    scrollTop: $('.tab-content').offset().top-600
                }, '1000');
		}

		$('#comment_form').formValidation({
	        framework: 'bootstrap',
	        icon: {
	            valid: 'null',
	            invalid: 'null',
	            validating: 'null',
	        },
	        fields: {
				comment: {
	                validators: {
	                    notEmpty: {
	                        message: 'Comment is required'
	                    },
	                    stringLength: {
	               			max: 250,
	                        message: 'Comment must be less than 250 characters',
	                    }
	                   
	                }
	            }
	        }

		}).on('success.form.fv', function(e) {

	        e.preventDefault();

		    var $form = $(e.target),
	        	$fv = $(e.target).data('formValidation'),
	        	$button = $form.data('formValidation').getSubmitButton();

	        	$('#captcha-modal').modal('hide');

	        switch ($button.attr('id')) {
	            case 'comment-publish':
	              	checkisSpammer(isSpam, $fv);
	              	$fv.disableSubmitButtons(false);
	                break;
	            case 'comment-publish-with-captcha':
	            	$fv.defaultSubmit();
	                break;
	        }
		            
		});

		var auth = "{% if auth is not empty %}true{% else %}false{% endif %}";

		if (auth == "false") {
				
			$(".rate").popover();

			$('body').on('click', function (e) {
			    $('.rate').each(function () {
			        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
			            $(this).popover('hide');
			        }
			    });
			});
		}
		
		$('#like').on('click', function(e) {
			e.preventDefault();
			var thisLike = this;

			if (auth == "true") {

		        $.ajax({
			        type: "POST",
			        data: { csrf_token: $('input[name="csrf_token"]').val(), id: "{{deal.id}}", mode: $(thisLike).hasClass("btn-success") ? "remove" : "like" },
			        url: "{{urlFor('deal_like')}}",
			        success: function() {

			        	var count = $('.likecount').data("num");

			        	if ($(thisLike).hasClass("btn-success") === false) {
			        		count =  count + 1;
			        	}

			        	if ($(thisLike).hasClass("btn-success")) {

			        		$(thisLike).removeClass('btn-success');
			        		$(thisLike).addClass('btn-default');

			        		$('.likecount').data('num', count-1);
			        		$('.likecount').html(' '+count-1);

			        	} else {

			        		$(thisLike).removeClass('btn-default');
			        		$(thisLike).addClass('btn-success');

			        		$('.likecount').data('num', count);
			        		$('.likecount').html(' '+count);
			        	}
			       		
			        	if ($("#dislike").hasClass("btn-danger")) {

			        		$("#dislike").removeClass('btn-danger');
			        		$("#dislike").addClass('btn-default');

			        		var dislikecount = $('.dislikecount').data('num');

			        		if (dislikecount !== 0) {
			        			$('.dislikecount').data('num', dislikecount-1);
			        			$('.dislikecount').html(' '+dislikecount-1);
			        		}
			        	}
			        },
			        error: function() {
			        	console.log('Server Error occured');
			        }
				});
			
			}
		});

		$('#dislike').on('click', function(e) {
			e.preventDefault();
			var thisLike = this;

			if (auth == "true") {
	        
	        $.ajax({
		        type: "POST",
		        data: { csrf_token: $('input[name="csrf_token"]').val(), id: "{{deal.id}}", mode: $(thisLike).hasClass("btn-danger") ? "remove" : "dislike"},
		        url: "{{urlFor('deal_like')}}",
		        success: function() {

		        	var count = $('.dislikecount').data('num');

		        	if ($(thisLike).hasClass("btn-danger") === false) {
		        		count =  count + 1;
		        	}

		        	if ($(thisLike).hasClass("btn-danger")) {

		        		$(thisLike).removeClass('btn-danger');
			        	$(thisLike).addClass('btn-default');

			        	$('.dislikecount').data('num', count -1);
			        	$('.dislikecount').html(' '+count - 1);

		        	} else {

			        	$(thisLike).removeClass('btn-default');
			        	$(thisLike).addClass('btn-danger');

			        	$('.dislikecount').data('num', count);
			        	$('.dislikecount').html(' '+count);
			        }

		        	if ($("#like").hasClass("btn-success")) {

		        		$("#like").removeClass('btn-success');
		        		$("#like").addClass('btn-default');

		        		var likecount = $('.likecount').data('num');

		        		if (likecount !== 0) {
		        			$('.likecount').data('num', likecount-1);
		        			$('.likecount').html(' '+likecount-1);
		        		}
		        	}
		        },
		        error: function() {
		        	console.log('Server Error occured');
		        }
			});

			}
		});

	});

	function checkisSpammer(isSpam, $fv) {
	       		
		if (isSpam === 1) {
			$('#captcha-modal').modal('show');
		} 

		if(isSpam === 0) {
			$('#captcha-modal').modal('hide');
			$fv.defaultSubmit();
		}
	}


</script>

{% endblock %}