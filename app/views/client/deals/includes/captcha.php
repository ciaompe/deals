<div class="modal fade" id="captcha-modal" data-keyboard="false" data-backdrop="static">
	<div class="modal-dialog" style="width:352px; height:auto">
		<div class="modal-content">

			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Are you a human ?</h4>
			</div>

			<div class="modal-body">
				<p style="margin-bottom:15px;">Please verify your humanity</p>
				<div class="g-recaptcha" data-theme="light" data-sitekey="{{sitekey}}"></div>
			</div>

			<div class="modal-footer">
				<button type="submit" class="btn btn-primary" id="comment-publish-with-captcha">Publish</button>
			</div>

		</div>
	</div>
</div>

