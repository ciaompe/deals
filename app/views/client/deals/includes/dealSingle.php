{% macro homeDeals(deal, baseUrl, images) %}
  <div class="col-md-2 column productbox">
      
      {% if deal.isNew() %}
          <span class="label label-danger label-new">New</span>
      {% endif %}
     
     <a href="{{ urlFor('deal_single_page', {'slug' : deal.dealUrl(), 'id' : deal.id} ) }}"> <img src="{{deal.getDealImage(baseUrl, images, 'small', true)}}"></a>
      
     <div class="producttitle"><a href="{{ urlFor('deal_single_page', {'slug' : deal.dealUrl(), 'id' : deal.id} ) }}">{{deal.limiText(deal.title, 50)}}</a></div>
     
     <div class="productprice"><div class="pull-right"><a href="{{urlFor('deal_url_redirect', {'id': deal.id})}}" class="btn btn-primary btn-sm" target="_blank">BUY</a></div><div class="pricetext">${{deal.price}}</div></div>
  
      <section class="product_box_footer">
          <span><i class="fa fa-thumbs-up {% if deal.liked(auth.id) %}liked{% endif %}"></i> <b>{{deal.likesCount()}}</b></span> | 
          <span><i class="fa fa-thumbs-down {% if deal.disLiked(auth.id) %}disliked{% endif %}"></i> <b>{{deal.dislikesCount()}}</b></span>
          <span class="pull-right"><i class="fa fa-comments"></i> <b>{{deal.commentCount()}}</b></span>
      </section>
  </div>
{% endmacro %}