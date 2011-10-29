<div id="assignMessageMarkingDiv">
	<fieldset>
		<legend>{lang}wcf.acp.user.assignMessageMarking{/lang}</legend>
		
		<div class="formRadio formGroup" id="markingIDDiv">
			<div class="formGroupLabel">
				<label>{lang}wcf.acp.user.assignMessageMarking.markingID{/lang}</label>
			</div>
			<div class="formGroupField">
				<fieldset>
					<legend>{lang}wcf.acp.user.assignMessageMarking.markingID{/lang}</legend>
						
					<div class="formField">
						<ul class="formOptionsLong">
							<li><label><input type="radio" name="markingID" value="0" {if $markingID == 0}checked="checked" {/if}/> <span>{lang}wcf.user.option.defaultMessageMarkingID.none{/lang}</span></label></li>
							{foreach from=$markings item=marking}
								<li><label><input type="radio" name="markingID" value="{@$marking->markingID}" {if $marking->markingID == $markingID}checked="checked" {/if}/> <span>{lang}{$marking->title}{/lang}</span></label></li>
							{/foreach}
						</ul>		
					</div>
					
					<div class="formFieldDesc" id="markingIDHelpMessage">
						<p>{lang}wcf.acp.user.assignMessageMarking.markingID.description{/lang}</p>
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
	function disableAssignMessageMarking() {
		hideOptions('assignMessageMarkingDiv');
	}
	
	// enable
	function enableAssignMessageMarking() {
		disableAll();
		showOptions('assignMessageMarkingDiv');
	}
	//]]>
</script>