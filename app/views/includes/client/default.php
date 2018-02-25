<!DOCTYPE html>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--><html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>{{main_config.sitename}} | {% block title %} {% endblock %}</title>
        <meta name="description" content="{{main_config.discription}}">
        <meta name="author" content="Best deals on earth">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="{{css}}/framework/validation/formValidation.min.css">
        <link rel='stylesheet' href='{{css}}/framework/alert/alert.css'>

        <link rel="stylesheet" href="{{css}}/client/themify-icons.css">
        <link rel="stylesheet" href="{{css}}/client/owl.carousel.css">
        <link rel="stylesheet" href="{{css}}/client/animate.min.css">
        <link rel="stylesheet" href="{{css}}/client/animsition.css">
        <link rel="stylesheet" href="{{css}}/client/plugins.min.css">

        <link rel="stylesheet" href="{{css}}/client/style.css">

        <!--[if lt IE 9]>
            <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

    {% block header %}{% endblock %} 

    </head>

    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->

        <!-- HEADER BLOCK -->
        {% include 'includes/client/header.php' %}
        <!-- END HEADER BLOCK -->


    <div class="site-wrapper animsition" data-animsition-in="fade-in" data-animsition-out="fade-out">
    
        {% spaceless %}
        <!-- Main container -->
        <div class="container">

            <!-- Falsh Message -->
            {% include 'includes/client/flash_message.php' %}
            <!-- END Flash -->
            
            <!-- Content Block -->
            {% block content %} {% endblock %}
            <!-- END BLOCK -->

        </div>
        {% endspaceless %}
        <!-- END container -->

        <div class="cta-box clearfix">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 col-sm-3 col-xs-12 pull-right" style="text-align:right">
                        <a class=
                        "btn btn-raised btn-danger ripple-effect btn-lg"
                        data-original-title="" href="#" title=""><i class=
                        "ti-check-box"></i> &nbsp; Sumbit Deal</a>
                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <h3>Found an awesome deal on the internet ?</h3>
                        <p>Great news! Submit the offer you found to us and stand a chance to win the submitted item for free!</p>
                    </div>
                </div>
            </div>
        </div><!-- /.CTA -->

        <!-- FOOTER -->

        <footer id="footer">
            <div class="container">
                <div class="col-sm-4">
                    <img alt="#" class="img-responsive logo" src="{{images}}/logo-dark.png">
                    <p>Best deals on planet, Saving you the hassle and trouble of going through 100's of web sites to find the best deal for the product you are intrested in. Start Now!</p>
                </div>
                <div class="col-sm-4">
                    <h5>OUR DEAL SOURCES</h5>
                    <ul class="tags">

                        {% if sources %}
                            {% for source in sources %}
                                <li>
                                    <a class="tag" href="{{urlFor('filter')}}?source={{source.id}}">{{source.name|capitalize}}</a>
                                </li>
                            {% endfor %}
                        {% endif %}
                       
                    </ul>
                </div>
                <div class="col-sm-2">
                    <h5>TOP CATEGORIES</h5>
                    <ul class="list-unstyled">

                        {% if categoryTree %}
                            {% for category in categoryTree %}
                                {% if category.parent == 0 %}
                                    <li><a href="{{urlFor('category_single', {'slug' : category.slug, 'id' : category.id})}}">{{category.name}}</a></li>
                                {% endif %}
                            {% endfor %}
                        {% endif %}
                        
                    </ul>
                </div>
                <div class="col-sm-2">
                    <h5>ABOUT US</h5>
                    <ul class="list-unstyled">
                        <li>Available Jobs</li>
                        <li>Sumbit Deal</li>
                        <li>Contact Us</li>
                        <li>History</li>
                        <li>Impressium</li>
                    </ul>
                </div>
            </div>
            <div class="btmFooter">
                <div class="container">
                    <div class="col-sm-7">
                        <p><strong>Copyright 2015</strong> {{main_config.sitename}} Developed With <i class="ti-heart"></i>
                        <strong>by Lucids Inc</strong></p>
                    </div>
                </div>
            </div>
        </footer>

        <!-- END FOOTER -->
    </div>
    <!-- END Animation -->


      
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  
    <script src="{{js}}/framework/validation/formValidation.min.js"></script>   
    <script src="{{js}}/framework/validation/formValidation.bootstrap.min.js"></script> 

    <script src="{{js}}/client/site.js"></script> 
    <script src="{{js}}/client/jquery.animsition.min.js"></script> 
    <script src="{{js}}/client/owl.carousel.min.js"></script> 
    <script src="{{js}}/client/jquery.flexslider-min.js"></script> 
    <script src="{{js}}/client/plugins.js"></script>
    <script src="{{js}}/client/social-buttons.js"></script> 
    <script src="{{js}}/client/lazysizes.min.js"></script> 

    <script src="{{js}}/framework/countdown/jquery.countdown.min.js"></script>

    <script src='{{js}}/framework/alert/alert.js'></script>
    <script src='https://www.google.com/recaptcha/api.js'></script>

   	<script>

        $('div.alert-success').delay(5000).slideUp(300);

        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });

        $('[data-countdown]').each(function() {
           var $this = $(this), finalDate = $(this).data('countdown');
           $this.countdown(finalDate, function(event) {
             $this.html(event.strftime('%D day%!d %H:%M:%S'));
           });
        });

        $('.deal-countdown').each(function() {
           var $this = $(this), finalDate = $(this).data('countdownt');
           $this.countdown(finalDate, function(event) {
             $this.html(event.strftime(
                '<b>%D</b> day%!d '+ '<b>%H</b> hr '+ '<b>%M</b> min '+ '<b>%S</b> sec '
            ));
           });
        });

        $(function(){
            $(".dropdown").hover(            
            function() {
                $('.dropdown-menu', this).stop( true, true ).fadeIn(500);
                $(this).toggleClass('open');
                $('b', this).toggleClass("caret caret-up");                
            },
            function() {
                $('.dropdown-menu', this).stop( true, true ).fadeOut(300);
                $(this).toggleClass('open');
                $('b', this).toggleClass("caret caret-up");                
            });
        });

        $(function(){
           // Javascript to enable link to tab
            var url = document.location.toString();
            if (url.match('#')) {
                 $('html, body').animate({ 
                    scrollTop: $('.tab-content').offset().top-600
                }, '1000');
                $('.nav-tabs a[href=#'+url.split('#')[1]+']').tab('show');
            } 

            // Change hash for page-reload
            $('.nav-tabs a').on('shown.bs.tab', function (e) {
                e.preventdefault();
                $('html, body').animate({ 
                    scrollTop: $('.tab-content').offset().top-200
                }, '1000');
            });

        });

        $(function(){
            var shrinkHeader = 300;
            
            $('.my-custom-nav-bar-icon').addClass('custom-shrink');

            $(window).scroll(function() {
                var scroll = getCurrentScroll();
                  if ( scroll >= shrinkHeader ) {
                       $('.header').addClass('shrink');
                       $('.header-col-border').addClass('shrink');
                       $('.my-custom-nav-bar-icon').removeClass('custom-shrink');
                    }
                    else {
                       $('.my-custom-nav-bar-icon').addClass('custom-shrink');
                       $('.header').removeClass('shrink');
                       $('.header-col-border').removeClass('shrink');
                    }
            });

            function getCurrentScroll() {
                return window.pageYOffset || document.documentElement.scrollTop;
            }
        });
            

    </script>

    {% block footer %}{% endblock %}

    </body>

</html>