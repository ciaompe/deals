<div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          
          <form class="avatar-form" action="{{urlFor('admin_account_propic')}}" enctype="multipart/form-data" method="POST">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title" id="avatar-modal-label">Change Profile Picture</h4>
            </div>
            <div class="modal-body">
              <div class="avatar-body">

              	<p>Select a profile picture for the user identification</p>

                <!-- Upload image and data -->
                <div class="avatar-upload">
                  <input type="hidden" name="csrf_token" value="{{csrf_token}}">
                  <input type="hidden" class="avatar-data" name="avatar_data">
                  <input type="file" class="avatar-input" id="avatarInput" name="avatar_file" style="display: none;">
                </div>

                <!-- Crop and preview -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="avatar-wrapper"></div>
                  </div>
                
                </div>

                <div class="row avatar-btns">
                 
                  <div class="col-xs-3">
                     <a class="btn btn-warning btn-block select-image">Select</a>
                  </div>

                  <div class="col-xs-3" style="padding-left:0">
                     <button type="submit" class="btn btn-success btn-block avatar-save" style="margin-left:-5px">Upload</button>
                  </div>

                </div>

                <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>

              </div>
            </div>
          </form>
          
        </div>
      </div>
</div>

	