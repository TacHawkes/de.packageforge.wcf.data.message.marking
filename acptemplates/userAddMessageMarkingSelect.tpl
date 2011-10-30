<select name="defaultMessageMarkingID" id="defaultMessageMarkingID">
	<option value="0"{if $defaultMessageMarkingID == 0} selected="selected"{/if}>{lang}wcf.user.option.defaultMessageMarkingID.none{/lang}</option>
	{foreach from=$markings item=marking}
		<option value="{@$marking->markingID}"
		{if $marking->markingID == $defaultMessageMarkingID} selected="selected"{/if}>{lang}{$marking->title}{/lang}</option>
	{/foreach}
</select>