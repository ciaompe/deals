<div class="panel-body bg-white shadow mTop-30 mBtm-50">
            
    <div class="card hovercard">
      <div class="cardheader"></div>

      <div class="avatar" id="crop-avatar">
        <div class="avatar-view" title="Change the avatar">
          <img alt="" src="{{auth.getAvatar(baseUrl, images, true)}}">
        </div>

        {% if auth.isLocalUser() %}
           {% include 'client/auth/account/includes/crop.php' %}
        {% endif %}
      </div>

      <div class="info">
          <div class="title">
             {{auth.getName()}}
          </div>
      </div>
   </div>

  <!-- Side Widget -->

  <div class="order-summary mTop-10">
       <table id="cart-summary" class="std table">
          <tbody>
             <tr>
                <td>Email</td>
                <td class="price">
                   {{auth.email}}
                </td>
             </tr>
             <tr style="">
                <td>
                   Joined On
                </td>
                <td class="price">
                   <span class="success">
                   {{auth.joined()}}
                   </span>
                </td>
             </tr>
             <tr class="cart-total-price ">
                <td>
                   First Name
                </td>
                <td class="price">
                  {{auth.f_name}}
                </td>
             </tr>
             <tr>
                <td>
                   Last Name
                </td>
                <td class="price" id="total-tax">
                   {{auth.l_name}}
                </td>
             </tr>
             <tr>
                <td>
                   Comments
                </td>
                <td class=" site-color" id="total-price">
                  {{auth.commentCount()}}
                </td>
             </tr>
             <tr>
                <td>
                   Watch List
                </td>
                <td class=" site-color" id="total-price">
                   {{auth.watchlistCount()}} Items
                </td>
             </tr>
          </tbody>
          <tbody>
          </tbody>
       </table>
    </div>

   <!-- /Side Widget -->

</div>