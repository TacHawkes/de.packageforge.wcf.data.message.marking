<div class="formElement">
	<div class="formField">
		<label><input onclick="if (this.checked) enableOptions('markAsTeamCSS'); else disableOptions('markAsTeamCSS')" type="checkbox" name="markAsTeam" id="markAsTeam" value="1" {if $markAsTeam == 1}checked="checked" {/if}/> {lang}wcf.acp.group.markAsTeam{/lang}</label>		
	</div>
	<div class="formFieldDesc hidden" id="markAsTeamHelpMessage">
		{lang}wcf.acp.group.markAsTeam.description{/lang}
	</div>
</div>

<div class="formElement" id="markAsTeamCSSDiv">
	<div class="formFieldLabel">
		<label for="markAsTeamCSS">{lang}wcf.acp.group.markAsTeamCSS{/lang}</label>
	</div>
	<div class="formField">
		<textarea id="markAsTeamCSS" name="markAsTeamCSS" rows="5">{@$markAsTeamCSS}</textarea>
	</div>
	<div class="formFieldDesc hidden" id="markAsTeamCSSHelpMessage">
		{lang}wcf.acp.group.markAsTeamCSS.description{/lang}
	</div>
</div>
<script type="text/javascript">
	//<![CDATA[
		inlineHelp.register('markAsTeam');
		inlineHelp.register('markAsTeamCSS');		
		{if $markAsTeam != 1}disableOptions('markAsTeamCSS');{/if}
	//]]>
</script>
