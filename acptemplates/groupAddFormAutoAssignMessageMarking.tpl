<fieldset>
	<legend>{lang}wcf.acp.group.messageMarking{/lang}</legend>
	
	<div class="formElement{if $errorType.markingID|isset} formError{/if}" id="markingIDDiv">
		<div class="formFieldLabel">
			<label for="markingID">{lang}wcf.acp.group.messageMarking.markingID{/lang}</label>
		</div>
		<div class="formField">
			<select name="markingID" id="markingID">
				<option value="0"{if $markingID == 0} selected="selected"{/if}>{lang}wcf.acp.group.messageMarking.markingID.none{/lang}</option>
				{foreach from=$markings item=marking}
					<option value="{@$marking->markingID}"
						{if $marking->markingID == $markingID} selected="selected"{/if}>{lang}{$marking->title}{/lang}</option>
				{/foreach}
			</select>
			{if $errorType.markingID|isset}
				<p class="innerError">
					{if $errorType.markingID == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
					{if $errorType.markingID == 'optionEnabled'}{lang}wcf.acp.group.messageMarking.markingID.error.optionEnabled{/lang}{/if}
				</p>
			{/if}
		</div>
		<div class="formFieldDesc hidden" id="markingIDHelpMessage">
			{lang}wcf.acp.group.messageMarking.markingID.description{/lang}
		</div>
	</div>
	<script type="text/javascript">//<![CDATA[
		inlineHelp.register('markingID');
	//]]></script>
	
	<div class="formElement" id="markingPriorityDiv">
		<div class="formFieldLabel">
			<label for="markingPriority">{lang}wcf.acp.group.messageMarking.markingPriority{/lang}</label>
		</div>
		<div class="formField">
			<input type="text" class="inputText" id="markingPriority" name="markingPriority" value="{@$markingPriority}" />
		</div>
		<div class="formFieldDesc hidden" id="markingPriorityHelpMessage">
			{lang}wcf.acp.group.messageMarking.markingPriority.description{/lang}
		</div>
	</div>
	<script type="text/javascript">//<![CDATA[
		inlineHelp.register('markingPriority');
	//]]></script>
</fieldset>