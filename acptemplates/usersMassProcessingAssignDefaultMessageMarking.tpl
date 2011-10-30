<div id="assignDefaultMessageMarkingDiv">
	<fieldset>
		<legend>{lang}wcf.acp.user.assignDefaultMessageMarking{/lang}</legend>
		
		<div class="formRadio formGroup" id="markingIDDiv">
			<div class="formGroupLabel">
				<label>{lang}wcf.acp.user.assignDefaultMessageMarking.markingID{/lang}</label>
			</div>
			<div class="formGroupField">
				<fieldset>
					<legend>{lang}wcf.acp.user.assignDefaultMessageMarking.markingID{/lang}</legend>
						
					<div class="formField">
						<select name="markingID" id="markingID">
							<option value="0"{if $markingID == 0} selected="selected"{/if}>{lang}wcf.user.option.defaultMessageMarkingID.none{/lang}</option>
							{foreach from=$markings item=marking}
								<option value="{@$marking->markingID}"
								{if $marking->markingID == $markingID} selected="selected"{/if}>{lang}{$marking->title}{/lang}</option>
							{/foreach}
						</select>						
					</div>
					
					<div class="formFieldDesc" id="markingIDHelpMessage">
						<p>{lang}wcf.acp.user.assignDefaultMessageMarking.markingID.description{/lang}</p>
					</div>
					<script type="text/javascript">
						//<![CDATA[
							inlineHelp.register('markingID');
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
	function disableAssignDefaultMessageMarking() {
		hideOptions('assignDefaultMessageMarkingDiv');
	}
	
	// enable
	function enableAssignDefaultMessageMarking() {
		disableAll();
		showOptions('assignDefaultMessageMarkingDiv');
	}
	//]]>
</script>