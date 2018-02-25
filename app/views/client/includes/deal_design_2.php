{% macro homeDeals(deal, baseUrl, images) %}

<div class="deal-entry green result-entry clearfix">
    <div class="entry-left col-sm-5 col-lg-6" style="border-right:1px solid #ededed">
        <a href="{{ urlFor('deal_single_page', {'slug' : deal.dealUrl(), 'id' : deal.id} ) }}">
        <div class="image bg-image entry-xs" data-image-src="{{deal.getDealImage(baseUrl, images, 'medium', true)}}" >
        	{% if deal.isNew() %}
                <span class="bought-new">NEW</span>
            {% endif %}

             <span class="bought">{{deal.getSourceName()|capitalize}}</span>
        </div>
        </a>
    </div>
    <!-- /.image wrap -->
    <div class="entry-right col-sm-7 col-lg-6">

        <div class="title" style="border-left:none;margin-bottom:0px;">
            <a href="{{ urlFor('deal_single_page', {'slug' : deal.dealUrl(), 'id' : deal.id} ) }}" title="{{deal.title}}">{{deal.limiText(deal.title, 70)}}</a>
        </div>
        <div class="entry-content">
            <div class="prices clearfix">
                <div class="procent" style="font-size: 29px;">
                    -{{deal.discount}}%
                </div>
                <div class="price">
                    <b style="font-size: 29px;">${{deal.getDiscount()|number_format(2)}}</b>
                </div>
                <div class="old-price">
                    <span>${{deal.price|number_format(2)}}</span>
                </div>
            </div>
            <p class="truncate visible-lg">{{deal.limiText(deal.discriptionText(), 200)}}</p>
        </div>
        <!--/.entry content -->


        <div class="feedback_bar clearfix search_page_thumbnail">
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
                    <i class="ti-timer"></i><span data-countdown="{{deal.getCountDown()}}"></span>
                 	{% endif %}
                </li>
                <li class="info_link col-sm-5 col-xs-6 cl-lg-4" style="text-align:right">
                    <a class="btn btn-success btn-raised btn-block btn-sm" href="{{ urlFor('deal_single_page', {'slug' : deal.dealUrl(), 'id' : deal.id} ) }}">View</a>
                </li>
            </ul>
        </footer>
    </div>
    <!-- /entry right -->
</div>
<!-- /results entry -->
<div class="clearfix"></div>


{% endmacro %}