<!-- SIDEBAR ADVERTISNG -->
<div class="smallFrame shadow bg-white">
    <div class="widget widget-add sidebar-ad-custom">
        <h3 class="heading-hr-new heading_hr">CATEGORIES</h3>
		
		{% if showAd(300, 250, baseUrl, images) is not null%}
			{{showAd(300, 250, baseUrl, images) | raw}}
		{% else %}
        <img src="http://placehold.it/300x250" alt="add" class="img-responsive">
		{% endif %}

    </div>
</div>
<!-- END SIDEBAR ADVERTISING -->

<!-- LATEST DEALS -->
<div class="smallFrame shadow bg-white">

    <div class="widget widget-featured">

        <h3 class="heading-hr-new heading_hr">ADVERTISE</h3>

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
               
</div>
<!-- END LATEST DEALS -->



