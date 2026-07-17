<div class="card">
	<div class="card-header">{mode}</div>
	<div class="card-body">
		<div id="alpFormResponse"></div>
<form hx-post="/admin-xhr/addons/plugin/alp/write/" hx-target="#alpFormResponse">
	<div class="row">

		<div class="col-md-6">
			<div class="mb-2">
				<label for="InputShorthand">{label_shorthand}</label>
				<input type="text" class="form-control" id="InputShorthand" name="alp_shorthand" value="{alp_shorthand}">
			</div>
		</div>
		<div class="col-md-6">
			<div class="mb-2">
				<label for="InputLang">{label_language}</label>
				{select_alp_langs}
			</div>
		</div>

		<div class="col">
			<label for="InputText">{label_text}</label>
			<textarea name="alp_text" class="form-control" id="InputText" rows="10">{alp_text}</textarea>
		</div>

	</div>

	<div class="d-flex mt-1">
	<input type="submit" name="save_alp_entry" value="{btn_value}" class="btn btn-default text-success">
		{btn_reset}
	<input type="hidden" name="alp_id" value="{alp_id}">
	<input type="hidden" name="csrf_token" value="{token}">

	</div>
</form>
	</div>
</div>