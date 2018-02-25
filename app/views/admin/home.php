{% extends 'includes/admin/default_two.php' %}

{% block title %} Home {% endblock %}

	
{% block pageheader %}
<div class="page-header page-header-green-sea">
  <h1><i class="fa fa-sort"></i>Dashboard</h1>
</div>
<!-- END PAGE HEADER -->

<ol class="breadcrumb">
  <li class="active">Home</li>
</ol>

<!-- END BREADCRUMB -->
{% endblock %}

{% block content %}	

  <div class="widget">
  
    <h3 class="section-title first-title"><i class="fa fa-tasks"></i> Statistics</h3>
    
    <br>
    <div class="row">
    
      <div class="col-lg-3 col-md-4 col-sm-6 text-center">
        <div class="widget-content-blue-wrapper changed-up">
          <div class="widget-content-blue-inner padded">
            <div class="pre-value-block"><i class="icon-dashboard"></i> Total Users</div>
            <div class="value-block">
              <div class="value-self">{{users}}</div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-lg-3 col-md-4 col-sm-6 text-center">
        <div class="widget-content-blue-wrapper changed-up">
          <div class="widget-content-blue-inner padded">
            <div class="pre-value-block"><i class="icon-dashboard"></i> Total Deals</div>
            <div class="value-block">
              <div class="value-self">{{deals}}</div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-lg-3 col-md-4 col-sm-6 text-center">
        <div class="widget-content-blue-wrapper changed-up">
          <div class="widget-content-blue-inner padded">
            <div class="pre-value-block"><i class="icon-dashboard"></i> Total Sources</div>
            <div class="value-block">
              <div class="value-self">{{sources}}</div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-lg-3 col-md-4 col-sm-6 text-center">
        <div class="widget-content-blue-wrapper changed-up">
          <div class="widget-content-blue-inner padded">
            <div class="pre-value-block"><i class="icon-dashboard"></i> Total Categories</div>
            <div class="value-block">
              <div class="value-self">{{categories}}</div>
            </div>
          </div>
        </div>
      </div>
      
    </div>
  </div>

  <div class="widget">
  
    <h3 class="section-title first-title"><i class="fa fa-comments"></i> Latest Comments</h3>

    <div class="row">

          <div class="col-md-12">
               <div class="widget-content-blue-wrapper changed-up">

                  <table id="comments" class="table table-striped table-bordered table-hover datatable">
                          <thead>
                                <th>User</th>
                                <th>Comment</th>
                                <th>IP Address</th>
                                <th>Action</th>
                          </thead>

                        <input type="hidden" name="csrf_token" value="{{csrf_token}}">
                         
                  </table>

                  <div class="row table-foot-bottom">
                    <div class="col-md-2">
                      <select id="commentStatus" name="cstatus" class="form-control">
                          <option value="">All Comments</option>
                          <option value="1">Approved user Comments</option>
                          <option value="0">Banned user comments</option>
                      </select>
                    </div>
                  </div>

                </div>
          </div>
      
    </div>

  </div>

  <div class="modal fade" id="comment-modal">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Edit Comment</h4>
          </div>
          <div class="modal-body" id="edit-comment-body" style="width:100%; position:relative">

            <form action="#" method="POST" id="comment_update_form">

              <div class="form-group">
                <textarea name="comment" class="form-control" cols="0" rows="4" id="editComment"></textarea>
              </div>

              <input type="hidden" name="csrf_token" value="{{csrf_token}}">
              <button type="submit" class="btn btn-primary">Save Changers</button>

              <div id="modal-error" style="color:red; float:right; margin-top:10px"></div>

            </form>
            
          </div>
      </div>
    </div>
  </div>
	
{% endblock %}

{% block footer %}

  <script type="text/javascript">

    $(function() {

        var table = $('#comments').DataTable({
          "pageLength": 10,
          "processing": true,
          "serverSide": true,
          "ajax": {
            url: "{{urlFor('admin_deal_comment_manage')}}",
            type: "POST",
            data: function(data) {
              data.csrf_token = $('input[name=csrf_token]').val();
              data.cstatus = $('#commentStatus option:selected').val();
            },
            error: function(){ 
            $(".comments-error").html("");
            $("#comments").append('<tbody class="comments-error"><tr><th colspan="6">No data found in the server</th></tr></tbody>');
            $("#comments_processing").css("display","none");
              
          }
        },
        fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            
            $(nRow).addClass('comment-'+aData[4]);

            $('td:eq(0)', nRow).html('<a href="'+aData[6]+'"><img width="32" height="32" src="'+aData[0]+'"></a>');
            $('td:eq(1)', nRow).html(aData[1]);
            $('td:eq(1)', nRow).addClass('cbody-'+aData[4]);
            $('td:eq(2)', nRow).html(aData[2]);
            $('td:eq(3)', nRow).html('<a class="btn btn-default btn-xs" style="margin-right:5px;" href="'+aData[3]+'" target="_blank">Show Deal</a><a href="#" class="edit-comment btn btn-xs btn-primary" onClick="getCommentBody(event,'+aData[4]+');" style="margin-right:5px;"><i class="fa fa-pencil-square-o"></i> edit</a><a href="#" onClick="deleteComment(event, '+aData[4]+')" class="delete-comment btn btn-xs btn-danger" data-id="'+aData[4]+'"><i class="fa fa-trash-o"></i> delete</a>');

        }
        });

        $('#commentStatus').on( 'change', function () {
          table.ajax.reload();
        });
    });

    
    function getCommentBody(e, cid) {
      e.preventDefault();
      $('#comment-modal').modal('toggle');
      $('#edit-comment-body').block({ 
        message: null
      }); 
      $.ajax({
          type: "POST",
          data: { csrf_token: $('input[name="csrf_token"]').val(), id : cid},
          url: "{{urlFor('admin_deal_comment_body')}}",
          success: function(data) {
            obj = jQuery.parseJSON(data);
            if (obj.status == 200) {
                $('#edit-comment-body').unblock();   
                $('#editComment').val();
                $('#editComment').val(obj.body);
              }
            },
          error: function() {
              $('#modal-error').html('Server Error occured');
          }
      });

      $('#comment_update_form')
          .formValidation('destroy')
          .formValidation({
                framework: 'bootstrap',
                icon: {
                    valid: 'null',
                    invalid: 'null',
                    validating: 'null'
                },
                fields: {
              comment: {
                        validators: {
                            notEmpty: {
                                message: 'Comment is required'
                            },
                            stringLength: {
                            max: 250,
                                message: 'Comment must be less than 250 characters',
                            }
                           
                        }
                    }
                }

          }).on('success.form.fv', function(e) {
                e.preventDefault();
                $.ajax({
                  type: "POST",
                  data: { csrf_token: $('input[name="csrf_token"]').val(), id: cid, comment: $('#editComment').val()},
                  url: "{{urlFor('admin_deal_comment_save')}}",
                  success: function(data) {
                    obj = jQuery.parseJSON(data);
                    if (obj.status == 200) {
                      $('.cbody-'+cid).html($('#editComment').val());
                      $('#comment-modal').modal('hide');
                    }
                  },
                  error: function() {
                    $('#modal-error').html('Server Error occured');
                  }
                });

        });
    }

    function deleteComment(e, cid) {

      e.preventDefault();
      swal({   
          title: "Are you sure?",
          text: "want to delete this comment ?",   
          type: "warning",   
          showCancelButton: true,   
          confirmButtonColor: "#DD6B55",   
          confirmButtonText: "Yes, delete it!",   
          cancelButtonText: "No, cancel !",   
          closeOnConfirm: true,   
          closeOnCancel: true
      },
      function(isConfirm){   
          if (isConfirm) { 

            $.ajax({
                type: "POST",
                data: { csrf_token: $('input[name="csrf_token"]').val(), id: cid},
                url: "{{urlFor('admin_deal_comment_delete')}}",
                success: function(data) {
                  obj = jQuery.parseJSON(data);
                  if (obj.status == 200) {
                    $('.comment-'+cid).remove();
                  }
                },
                error: function() {
                  console.log('Server Error occured');
                }
            });
              
          } 
      });
    }

  </script>
    

{% endblock %}