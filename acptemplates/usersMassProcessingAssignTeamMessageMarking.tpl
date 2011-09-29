<div id="assignTeamMessageMarkingDiv">
	<fieldset>
		<legend>{lang}wcf.acp.user.assignTeamMessageMarking{/lang}</legend>
		
		<div class="formRadio formGroup" id="markTeamMessageGroupIDDiv">
			<div class="formGroupLabel">
				<label>{lang}wcf.acp.user.assignTeamMessageMarking.markTeamMessageGroupID{/lang}</label>
			</div>
			<div class="formGroupField">
				<fieldset>
					<legend>{lang}wcf.acp.user.assignTeamMessageMarking.markTeamMessageGroupID{/lang}</legend>
						
					<div class="formField">
						<ul class="formOptionsLong">
							<li><label><input onclick="showTeamMarkingPreview(0)" type="radio" name="markTeamMessageGroupID" value="0" {if $markTeamMessageGroupID == 0}checked="checked" {/if}/> <span>{lang}wcf.user.option.markTeamMessageGroupID.none{/lang}</span></label></li>
							{foreach from=$markings item=marking}
								<li><label><input type="radio" name="markTeamMessageGroupID" value="{@$marking.groupID}" {if $marking.groupID == $markTeamMessageGroupID}checked="checked" {/if}/> <span>{lang}{$marking.groupName}{/lang}</span></label></li>
							{/foreach}
						</ul>		
					</div>
					
					<div class="formFieldDesc" id="markTeamMessageGroupIDHelpMessage">
						<p>{lang}wcf.acp.user.assignTeamMessageMarking.markTeamMessageGroupID.description{/lang}</p>
					</div>
					<script type="text/javascript">
						//<![CDATA[
							inlineHelp.register('markTeamMessageGroupID');
						//]]>
					</script>																		
				</fieldset>
			</div>
		</div>						
	</fieldset>
</div>

<script type="text/javascript">
	//<![CDATA[
	// disable
	function disableAssignTeamMessageMarking() {
		hideOptions('assignTeamMessageMarkingDiv');
	}
	
	// enable
	function enableAssignTeamMessageMarking() {
		disableAll();
		showOptions('assignTeamMessageMarkingDiv');
	}
	//]]>
</script>