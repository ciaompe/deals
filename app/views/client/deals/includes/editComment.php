<div class="modal fade" id="comment-modal">
	<div class="modal-dialog">
		<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h4 class="modal-title">Edit Review</h4>
				</div>
				<div class="modal-body">

					<form action="#" method="POST" id="comment_update_form">

						<div class="form-group">
							<textarea name="comment" class="form-control" cols="0" rows="8" id="editComment"></textarea>
						</div>

						<input type="hidden" name="csrf_token" value="{{csrf_token}}">
						<button type="submit" class="btn btn-primary">Save Changers</button>

					</form>
					
				</div>
		</div>
	</div>
</div>