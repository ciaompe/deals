{% macro homeDeals(deal, baseUrl, images) %}

<div class="col-sm-3">

    <div class="deal-entry green">
        <div class="image">
            <a href="{{ urlFor('deal_single_page', {'slug' : deal.dealUrl(), 'id' : deal.id} ) }}" title="{{deal.title}}"><img alt="#" class="img-responsive lazyload" data-src="{{deal.getDealImage(baseUrl, images, 'medium', true)}}" style="width:100% !important;"></a> 
           
            {% if deal.isNew() %}
                <span class="bought" style="background-color: #ff5722">NEW</span>
            {% endif %}

            <span class="bought-source">{{deal.getSourceName()|capitalize}}</span>

        </div><!-- /.image -->
        <div class="title">
            <a href="{{ urlFor('deal_single_page', {'slug' : deal.dealUrl(), 'id' : deal.id} ) }}" title="{{deal.title}}">{{deal.limiText(deal.title, 40)}}</a>
        </div>
        <div class="entry-content">
            <div class="prices clearfix">
                <div class="procent">
                    -{{deal.discount}}%
                </div>
                <div class="price">
                    <i class="ti-money"></i> <b>${{deal.getDiscount()|number_format(2)}}</b>
                </div>
                <div class="old-price">
                    <span><i class="ti-money"></i>
                    ${{deal.price|number_format(2)}}</span>
                </div>
            </div>
        </div><!--/.entry content -->
        <div class="feedback_bar clearfix">
            <div class="valueInfo">
                <a href="" style="cursor: default">
                    <p class="value"><i class="ti-thumb-up"></i> <small>{{deal.likesCount()}}</small></p>
                </a>
                <a href="" style="cursor: default">
                    <p class="value"><i class="ti-thumb-down"></i> <small>{{deal.dislikesCount()}}</small></p>
                </a>
                <a href="{{ urlFor('deal_single_page', {'slug' : deal.dealUrl(), 'id' : deal.id} ) }}#reviews" class="comment_btn">
                    <p class="value"><i class="ti-comment"></i> <small>{{deal.commentCount()}}</small></p>
                </a>
            </div>
        </div>
        <footer class="info_bar clearfix">
            <ul class="unstyled list-inline row">
                <li class="time col-sm-7 col-xs-6 col-lg-8" style="text-align:left">
                    {% if deal.type == 'count' %}
                    <i class="ti-timer"></i> 
                    <b><span data-countdown="{{deal.getCountDown()}}"></span></b>
                    {% endif %}

                </li>
                <li class="info_link col-sm-5 col-xs-6 col-lg-4" style="text-align:right">
                    <a class="btn btn-default btn-sm" href="{{ urlFor('deal_single_page', {'slug' : deal.dealUrl(), 'id' : deal.id} ) }}" style="border: 1px solid #ededed">View</a>
                </li>
            </ul>
        </footer>
    </div>

</div>

{% endmacro %}