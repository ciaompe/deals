{% extends 'email/default.php' %}

{% block container %}

<p style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; font-weight: normal; margin: 0 0 10px; padding: 0;">Forgot your password?  No worries, <a href="{{url}}{{urlFor('auth_reset')}}?token={{ token|url_encode }}">click here</a> to reset your password.</p>
<br>
<table style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 1.6em; font-weight: normal; margin: 0 0 10px; padding: 0;">
  <tr>
    <td>
      <a href="{{url}}{{urlFor('auth_reset')}}?token={{ token|url_encode }}" style="font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 100%; line-height: 2; color: #ffffff; border-radius: 5px; display: inline-block; cursor: pointer; font-weight: bold; text-decoration: none; background: #348eda; margin: 0; padding: 5px 10px;">Reset Password</a>
    </td>
  </tr>
</table>

{% endblock %}