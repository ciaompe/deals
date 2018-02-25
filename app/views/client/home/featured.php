<!-- Featured Items -->
<div class="slider">
    <div class="row">
        <div class="flexslider" id="grid-slider">

            <ul class="slides">

                {% for deal in featuredDeals %}
            
                <li>

                    <div class="col-sm-7 col-lg-7 omega">
                        <article class="bg-image entry-lg" data-image-src="{{deal.getDealImage(baseUrl, images, 'large', true)}}">
                            <div class="deal-short-entry">
                                <p>{{deal.limiText(deal.title, 60)}}</p>
                            </div>
                        </article>
                    </div>

                    <div class="col-sm-5 col-lg-5 alpha entry-lg deal-lg-content">
                        <div class="buyPanel animated fadeInLeft bg-white bordered Aligner">
                        <div class="content">
                                <div class="deal-content">
                                    <h3>{{deal.limiText(deal.title, 40)}}</h3>
                                    <p>{{deal.limiText(deal.discriptionText(), 100)}}</p>
                                </div>
                                <ul class="deal-price list-unstyled list-inline">
                                    <li class="price">
                                        <h3>${{deal.getDiscount()| number_format(2) }}</h3>
                                    </li>
                                    <li class="buy-now">
                                        <a href="{{ urlFor('deal_single_page', {'slug' : deal.dealUrl(), 'id' : deal.id} ) }}" class="btn btn-success btn-md">
                                            <i class="ti-new-window"></i>
                                            View
                                        </a>
                                    </li>
                                </ul>
                                <div class="dealAttributes">
                                    <div class="valueInfo bg-light shadow">
                                        <div class="value">
                                            <p class="value">${{deal.price}}</p>
                                            <p class="text">Value</p>
                                        </div>
                                        <div class="discount">
                                            <p class="value">{{deal.discount}}%</p>
                                            <p class="text">Discount</p>
                                        </div>
                                        <div class="save">
                                            <p class="value">${{(deal.price - deal.getDiscount())|number_format(2)}}</p>
                                            <p class="text">SAVINGS</p>
                                        </div>
                                    </div>

                                   
                                    <!-- /.value info -->
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
                                        <li><p><i class="ti-thumb-up"></i><b> {{deal.likesCount()}}</b> Likes</p></li>
                                        <li><p><i class="ti-thumb-down"></i><b> {{deal.dislikesCount()}}</b> Dislikes</p></li>
                                        <li><p><i class="ti-comment"></i><b> {{deal.commentCount()}}</b> Comments</p></li>
                                    </ul>

                                    <div class="social-sharing text-center" data-permalink="{{constant('APP_HOST')}}{{ urlFor('deal_single_page', {'slug' : deal.dealUrl(), 'id' : deal.id} ) }}">
                                    <!-- https://developers.facebook.com/docs/plugins/share-button/ -->
                                        <a class="share-facebook" href="http://www.facebook.com/sharer.php?u={{constant('APP_HOST')}}{{ urlFor('deal_single_page', {'slug' : deal.dealUrl(), 'id' : deal.id} ) }}" target="_blank">
                                            <span class="icon icon-facebook"></span>
                                            <span class="share-title">Facebook</span>
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
                                    <!--/.social sharing -->
                                </div>
                            </div>
                        </div><!-- /#buypanel -->
                    </div>

                </li>
                {% endfor %}

                
            </ul>

        </div>
    </div>
</div><!-- /slider -->