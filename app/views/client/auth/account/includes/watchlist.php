 <table class="cart-contents">
      <thead>
          <tr>
              <th class="hidden-xs">
                  Image
              </th>
              <th>
                  Title
              </th>
          </tr>
      </thead>
      <tbody>

        {% for deal in deals %}
          <tr>
              <td class="image hidden-xs">
                  <img src="{{deal.getDealImage(baseUrl, images, 'small', true)}}" alt="product">
              </td>
              <td class="details">
                  <div class="clearfix">
                      <div class="pull-left">
                          <a href="{{ urlFor('deal_single_page', {'slug' : deal.dealUrl(), 'id' : deal.id} ) }}" class="title">
                            {{deal.limiText(deal.title, 40)}}
                          </a>
                      </div>
                      <div class="pull-right">

                              <a href="{{urlFor('deal_remove_list', {'id' : deal.id})}}?outside=false" class="btn btn-danger ripple-effect delete" style="min-width: 0px; font-size: 15px;">
                                  <i class="ti-trash"></i>
                              </a>
                      </div>
                  </div>
              </td>
    
          </tr>
        
        {% else %}

        <tr>
          <td>NO DEALS</td>
          <td>NO DEALS</td>
        </tr>

        {% endfor %}


      </tbody>

  </table>

  {% include 'client/auth/account/includes/pagination.php' %}