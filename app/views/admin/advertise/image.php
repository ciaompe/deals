<div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-lg img-crop-modal">
        <div class="modal-content">
          
          <form class="avatar-form" action="{{formUrl}}" enctype="multipart/form-data" method="POST">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title" id="avatar-modal-label" style="text-align:left; padding-left:15px">Upload an image</h4>
            </div>
            <div class="modal-body">
              <div class="avatar-body">
                <!-- Upload image and data -->
                <div class="avatar-upload">
                  <input type="hidden" name="csrf_token" value="{{csrf_token}}">
                  <input type="hidden" class="avatar-data" name="avatar_data">
                    <input type="hidden" name="adsize" id="ad-size-input" value="">
                     <input type="hidden" name="adtype" id="ad-type-input" value="">
                  <input type="file" class="avatar-input" id="avatarInput" name="avatar_file" style="display: none;">
                </div>

                <!-- Crop and preview -->
                <div class="row">

                  <div class="col-md-9">
                    <div class="avatar-wrapper" style="height:400px; margin-top:0px;"></div>
                  </div>

                  <div class="col-md-3">

                      <h4 style="text-align:left;float:left; width:100%; border-bottom: 2px dotted #e7e7e7; padding-bottom:10px; margin-bottom:15px; margin-top:0px;">Ad image size</h4>

                      <div class="input-group input-group" style="margin-bottom:10px;">
                        <label class="input-group-addon" for="dataWidth">Width</label>
                        <input type="text" class="form-control" id="adWidth" placeholder="width" value="" disabled>
                        <span class="input-group-addon">px</span>
                      </div>

                      <div class="input-group input-group" style="margin-bottom:10px;">
                        <label class="input-group-addon" for="dataHeight">Height</label>
                        <input type="text" class="form-control" id="adHeight" placeholder="height" value="" disabled>
                        <span class="input-group-addon">px</span>
                      </div>

                      <h4 style="text-align:left;float:left; width:100%; border-bottom: 2px dotted #e7e7e7; padding-bottom:10px; margin-bottom:15px;">Cropped area size</h4>

                      <div class="input-group input-group" style="margin-bottom:10px;">
                        <label class="input-group-addon" for="dataWidth">Width</label>
                        <input type="text" class="form-control" id="dataWidth" placeholder="width" value="">
                        <span class="input-group-addon">px</span>
                      </div>

                      <div class="input-group input-group" style="margin-bottom:10px;">
                        <label class="input-group-addon" for="dataHeight">Height</label>
                        <input type="text" class="form-control" id="dataHeight" placeholder="height" value="">
                        <span class="input-group-addon">px</span>
                      </div>

                  </div>
                
                </div>

                <div class="row avatar-btns">
                     <a class="btn btn-warning select-image" style="width:auto; float:left; margin-left:15px;">Select</a>
                     <button type="submit" class="btn btn-success avatar-save" style="width:auto; float:left; margin-left:10px;">Upload</button>
                </div>

                <div class="loading" aria-label="Loading" role="img" tabindex="-1"></div>

              </div>
            </div>
          </form>
          
        </div>
      </div>
</div>

  