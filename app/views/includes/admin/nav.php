<div class="text-center">
  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </button>
</div>
<!-- END MOBILE TOGGLE ICON -->

<div class="side-bar-wrapper collapse navbar-collapse navbar-ex1-collapse">
    <div class="relative-w">

      <ul class="side-menu side-menu-green-sea">

        <li class='current'>
          <a class='current' href="{{urlFor('admin_home')}}">
            <i class="fa fa-dashboard"></i>Dashboard
          </a>
        </li>

        <!-- END DASHBOARD -->

        <li>
          <a href="{{urlFor('admin_category_manager')}}">
            <i class="fa fa-list-ul"></i> Category Manager
          </a>
        </li>

        <!-- END CATEGORY MANAGER -->

        <li>

          <a href="{{urlFor('admin_category_manager')}}" class="is-dropdown-menu">
            <span class="badge pull-right"></span>
            <i class="fa fa-sitemap"></i> Sources
          </a>
      <!-- END SOURCES -->

          <ul>

            <li>
              <a href="{{urlFor('admin_deals_source_create')}}">
                <i class="fa fa-plus"></i>
                Add New Source
              </a>
            </li>
            <!-- END NEW SOURCE -->

            <li>
              <a href="{{urlFor('admin_deals_source_manage')}}">
                <i class="fa fa-table"></i>
                Manage Sources
              </a>
            </li>
            <!-- END MANEG SOURCES -->

          </ul>

        </li>

        <li>

          <a href="#" class="is-dropdown-menu">
            <span class="badge pull-right"></span>
            <i class="fa fa-calendar-plus-o"></i> Deals
          </a>
          <!-- END DEALS -->

          <ul>

            <li>
              <a href="{{urlFor('admin_deals_create')}}">
                <i class="fa fa-plus"></i>
                Add New Deal
              </a>
            </li>
            <!-- END NEW DEAL -->

            <li>
              <a href="{{urlFor('admin_deals_manage')}}">
                <i class="fa fa-table"></i>
                Manage Deals
              </a>
            </li>
            <!-- END MANAGE DEALS -->

          </ul>

        </li>

         </li>

          <li>

          <a href="{{urlFor('admin_advertise')}}">
            <span class="badge pull-right"></span>
            <i class="fa fa-picture-o"></i> Advertise
          </a>
          <!-- END ad units-->
        </li>

        <li>

        <li>

          <a href="#" class="is-dropdown-menu">
            <span class="badge pull-right"></span>
            <i class="fa fa-users"></i> Users
          </a>
          <!-- END USERS -->

          <ul>

            <li>
              <a href="{{urlFor('admin_create_user')}}">
                <i class="fa fa-user-plus"></i>
                Add user
              </a>
            </li>
            <!-- END ADD USER -->

            <li>
              <a href="{{urlFor('admin_user_manage')}}">
                <i class="fa fa-table"></i>
                Manage Users
              </a>
            </li>
            <!-- END MANAGE USERS -->

          </ul>
        </li>

        <li>
        
          <a href="#" class="is-dropdown-menu">
            <span class="badge pull-right"></span>
            <i class="fa fa-user"></i>{{admin.getName()}}
          </a>
          <!-- END LOGGED IN USERS-->

           <ul>

            <li>
              <a href="{{urlFor('admin_account_update')}}">
                <i class="fa fa-pencil-square"></i>
                Update Profile
              </a>
            </li>
            <!-- END UPDATE PROFILE -->

            <li>
              <a href="{{urlFor('admin_account_password')}}">
                <i class="fa fa-cog"></i>
                Change Password
              </a>
            </li> 
            <!-- END CHANGE PASSWORD -->

            <li>
              <a href="{{urlFor('admin_account_logout')}}">
               <i class="fa fa-sign-out"></i>
               Logout
              </a>
            </li>
            <!-- END LOGOUT -->

          </ul>
        </li>

      </ul>
      
    </div>
</div>
<!-- END sidebar wrapper -->