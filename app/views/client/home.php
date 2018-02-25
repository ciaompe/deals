{% extends 'includes/client/default.php' %}

{% block title %} Home {% endblock %}

{% block header%}

<!-- START - Facebook Open Graph, Google+ and Twitter Card Tags 1.6.3 -->
<meta property="og:site_name" content="{{main_config.sitename}}"/>
<meta property="og:title" content="{{homeDeal.title}}"/>
<meta property="og:description" content="{{homeDeal.limiText(homeDeal.discriptionText(), 300)}}"/>
<meta itemprop="name" content="{{homeDeal.title}}"/>
<meta property="og:url" content="{{constant('APP_HOST')}}{{ urlFor('deal_single_page', {'slug' : homeDeal.dealUrl(), 'id' : homeDeal.id} ) }}"/>
<meta property="og:type" content="article"/>
<!-- END - Facebook Open Graph, Google+ and Twitter Card Tags -->

<meta property="og:image" content="{{homeDeal.getDealImage(baseUrl, images, 'medium', true)}}"/>
<meta property="og:image:width" content="{{config.deal.medium.width}}" />
<meta property="og:image:height" content="{{config.deal.medium.height}}" />
<meta property="og:locale" content="en_us"/>

<!-- Twitter card meta tags -->
<meta name="twitter:card" content="{{homeDeal.getDealImage(baseUrl, images, 'large', true)}}">
<meta name="twitter:site" content="{{main_config.sitename}}">
<meta name="twitter:title" content="{{homeDeal.title}}">
<meta name="twitter:description" content="{{homeDeal.limiText(homeDeal.discriptionText(), 300)}}"/>
<meta name="twitter:image:src" content="{{homeDeal.getDealImage(baseUrl, images, 'large', true)}}">

<!-- Google plus and Pinterest meta tags -->
<meta itemprop="name" content="{{homeDeal.title}}">
<meta itemprop="description" content="{{homeDeal.limiText(homeDeal.discriptionText(), 300)}}">
<meta itemprop="image" content="{{homeDeal.getDealImage(baseUrl, images, 'medium', true)}}">


{% endblock %}

{% block content %}

{% import "client/includes/deal_design_1.php" as deals %}

<div class="row">
    <div class="col-sm-3 sidebar">
       	{% include 'client/home/category.php' %}
    </div>
    <div class="col-sm-9">
      	{% include 'client/home/featured.php' %}
    </div><!-- /.col -9  -->
</div><!-- /.row -->

<section id="page">

	<div class="mTop-30">

        <div class="row">
            <div class="col-sm-12 clearfix">
                <div class="hr-link">
                    <hr class="mBtm-50" data-symbol="LATEST DEALS">
                    <div class="view-all">
                        <a href="{{urlFor('deal_home')}}">VIEW ALL</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

        	{% for deal in latestDeals %}
				{{ deals.homeDeals(deal,  baseUrl, images) }}
			{% endfor %}
            
        </div><!--/.row -->

    </div><!--/.frame -->

    <div class="row show-ad">
        <div class="col-sm-12">
          	{{showAd(728, 90, baseUrl, images)|raw}}
        </div>
    </div><!--/.row -->

    <div class="mTop-30">

	    <div class="row">
	            <div class="col-sm-12 clearfix">
	                <div class="hr-link">
	                    <hr class="mBtm-50" data-symbol="ENDING SOON DEALS">
	                    <div class="view-all">
	                        <a href="{{urlFor('deal_home')}}">VIEW ALL</a>
	                    </div>
	                </div>
	            </div>
	    </div>

        <div class="row">

        	{% for deal in expireSoon %}
				{{ deals.homeDeals(deal,  baseUrl, images) }}
			{% endfor %}
            
        </div><!--/.row -->
    </div><!--/.frame -->

</section>


{% endblock %}

