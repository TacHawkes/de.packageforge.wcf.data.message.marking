<ul class="formOptionsLong">
	<li><label><input onclick="showTeamMarkingPreview(0)" type="radio" name="markTeamMessageGroupID" value="0" {if $markTeamMessageGroupID == 0}checked="checked" {/if}/> <span>{lang}wcf.user.option.markTeamMessageGroupID.none{/lang}</span></label></li>
	{foreach from=$markings item=marking}
		<li><label><input type="radio" name="markTeamMessageGroupID" value="{@$marking.groupID}" {if $marking.groupID == $markTeamMessageGroupID}checked="checked" {/if}/> <span>{lang}{$marking.groupName}{/lang}</span></label></li>
	{/foreach}
</ul>	