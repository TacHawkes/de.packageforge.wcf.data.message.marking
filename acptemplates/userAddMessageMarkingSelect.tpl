<ul class="formOptionsLong">
	<li><label><input type="radio" name="defaultMessageMarkingID" value="0" {if $defaultMessageMarkingID == 0}checked="checked" {/if}/> <span>{lang}wcf.user.option.defaultMessageMarkingID.none{/lang}</span></label></li>
	{foreach from=$markings item=marking}
		<li><label><input type="radio" name="defaultMessageMarkingID" value="{@$marking->markingID}" {if $marking->markingID == $defaultMessageMarkingID}checked="checked" {/if}/> <span>{lang}{$marking->title}{/lang}</span></label></li>
	{/foreach}
</ul>	