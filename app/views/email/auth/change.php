{% extends 'email/default.php' %}

{% block container %}

<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; font-weight: normal; margin: 0 0 10px; padding: 0;">Congrats, Your account password has been change successfully,</p> 

<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; font-weight: normal; margin: 0 0 10px; padding: 0;">if you didn’t authorise this action please contact our support team immediately.</p>
  
<table style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; font-weight: normal; margin: 0 0 10px; padding: 0;">
  <tr>
    <td>
      <a href="{{url}}{{urlFor('contact')}}" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 2; color: #ffffff; border-radius: 5px; display: inline-block; cursor: pointer; font-weight: bold; text-decoration: none; background: #348eda; margin: 0; padding: 5px 10px;">Contact us</a>
    </td>
  </tr>
</table>

{% endblock %}
