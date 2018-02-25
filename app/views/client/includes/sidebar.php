<div class="widget widget-menu">
     <h3 class="heading-hr-new heading_hr">CATEGORIES</h3>
    <!-- Sidebar navigation -->

    {% macro homeCategory(category, baseUrl, images) %}

        <li {% if category.children|length %}class="dropdown"{% endif %}>

            <a data-target="#" {% if category.children|length %}class="ripple-effect dropdown-toggle disabled" data-toggle="dropdown"{% endif %} href="{{urlFor('category_single', {'slug' : category.slug, 'id' : category.id})}}">
        
                {% if category.image is not null %}
                    {% if file_exists(constant('APP_PATH') ~category.image) %}    
                     <img alt="" src="{{baseUrl}}{{category.image}}" style="height:32px; margin-right:16px;">
                    {% endif %}
                {% endif %}

                <span style="padding-left: 16px;border-left: 1px solid #eaeaea;">{{category.name}}</span> 

                {% if category.children|length %}
                   <span class="caret"></span>
                {%endif%} 
            </a>
            
            {% if category.children|length %}
                <ul class="dropdown-menu">
                    {% for child in category.children %}
                        {{ _self.homeCategory(child, baseUrl, images) }}
                    {% endfor %}
                </ul>
            {% endif %}

        </li>

    {% endmacro %}
    
    {% if categoryTree %}
        <ul class="nav sidebar-nav">
            {% for category in categoryTree %}
                {{ _self.homeCategory(category, baseUrl, images) }}
            {% endfor %}
        </ul><!-- Sidebar divider -->
    {% endif %}


</div>
<!-- END WIDGET -->

<!-- SIDEBAR ADVERTISNG -->
<div class="widget widget-add sidebar-ad-custom">
    <h3 class="heading-hr-new heading_hr">ADVERTISE</h3>
	
	{% if showAd(300, 600, baseUrl, images) is not null%}
		{{showAd(300, 600, baseUrl, images) | raw}}
	{% else %}
    <img src="http://placehold.it/300x250" alt="add" class="img-responsive">
	{% endif %}

</div>
<!-- END SIDEBAR ADVERTISING -->

<!-- LATEST DEALS -->
<div class="widget widget-featured">

    <h3 class="heading-hr-new heading_hr">LATEST DEALS</h3>

    {% for deal in sidebarDeals %}
    <div class="media media-sm entry-rating">
        <div class="media-left">
            <img class="media-object lazyload" data-src="{{deal.getDealImage(baseUrl, images, 'small', true)}}" alt="blog-thumb">
        </div>
        <div class="media-body">
            <h5 class="media-heading">
                      <a href="{{ urlFor('deal_single_page', {'slug' : deal.dealUrl(), 'id' : deal.id} ) }}">
                        {{deal.limiText(deal.title, 40)}}
                      </a>
                    </h5>
            <ul class="list-inline">
                <li>
                    <p class="price line-trough">
                        ${{deal.price|number_format(2)}}
                    </p>
                </li>
                <li>
                    <p class="price">
                       ${{deal.getDiscount()|number_format(2)}}
                    </p>
                </li>
            </ul>
        </div>
    </div>
    {% endfor %}
    <!-- /entry rating -->
</div>
<!-- END LATEST DEALS -->

{% if hotDeal is not null %}
<div class="widget">

    <h3 class="heading-hr-new heading_hr">HOT DEAL</h3>

	<div class="deal-entry green deal-entry-sm" style="float:none">
	    <div class="offer-discount">
	        {{hotDeal.discount}}%
	    </div>
	    <div class="image ripple-effect">
	        <a href="{{ urlFor('deal_single_page', {'slug' : hotDeal.dealUrl(), 'id' : hotDeal.id} ) }}" target="_blank" title="#"><img alt="#" class="img-responsive lazyload" data-src="{{hotDeal.getDealImage(baseUrl, images, 'medium', true)}}">
	        </a>
	        <span class="bought">{{hotDeal.getSourceName()|capitalize}}</span>
	        <div class="caption">
	            <h5 class="media-heading">
	                <a href="{{ urlFor('deal_single_page', {'slug' : hotDeal.dealUrl(), 'id' : hotDeal.id} ) }}">{{hotDeal.limiText(hotDeal.title, 70)}}</a>
	            </h5>
	        </div>
	    </div>
	    <footer class="clearfix">
	        <div class="valueInfo shadow">
	            <div class="value">
	                <p class="value">
	                    ${{hotDeal.price|number_format(2)}}</p>
	                <p class="text">
	                    Value</p>
	            </div>
	            <div class="discount">
	                <p class="value">
	                    {{hotDeal.discount}}%</p>
	                <p class="text">
	                    Discount</p>
	            </div>
	            <div class="save">
	                <p class="value">
	                    ${{(hotDeal.price - hotDeal.getDiscount()) |number_format(2)}}</p>
	                <p class="text">
	                    SAVINGS</p>
	            </div>
	        </div>
	    </footer>
	</div>
	<!--/.deal entry -->
</div>

<div class="widget" style="text-align: center">
{{showAd(320, 100, baseUrl, images) | raw}}
</div>

{% endif %}

