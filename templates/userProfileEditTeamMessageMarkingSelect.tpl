<script type="text/javascript">
	//<![CDATA[
		var markingGroupIDs = new Array();
		{foreach from=$markings item=marking}
		markingGroupIDs.push({$marking.groupID});
		{/foreach}

		function showTeamMarkingPreview(groupID) {
			markingGroupIDs.each(function(id) {
				if (id != groupID) {
					$('teamMarkingPreview' + id).hide();
				}
				else {
					$('teamMarkingPreview' + id).show();
				}
			});
		}
	//]]>
</script>
<ul class="formOptionsLong">
	<li><label><input onclick="showTeamMarkingPreview(0)" type="radio" name="markTeamMessageGroupID" value="0" {if $markTeamMessageGroupID == 0}checked="checked" {/if}/> <span>{lang}wcf.user.option.markTeamMessageGroupID.none{/lang}</span></label></li>
	{foreach from=$markings item=marking}
		<li><label><input onclick="showTeamMarkingPreview({@$marking.groupID})" type="radio" name="markTeamMessageGroupID" value="{@$marking.groupID}" {if $marking.groupID == $markTeamMessageGroupID}checked="checked" {/if}/> <span>{lang}{$marking.groupName}{/lang}</span></label></li>
	{/foreach}
</ul>

{if $this->getStyle()->getVariable('messages.color.cycle')}
	{cycle name=messageCycle values='2,1' print=false}
{else}
	{cycle name=messageCycle values='1' print=false}
{/if}

{if $this->getStyle()->getVariable('messages.sidebar.color.cycle')}
	{if $this->getStyle()->getVariable('messages.color.cycle')}
		{cycle name=postCycle values='1,2' print=false}
	{else}
		{cycle name=postCycle values='3,2' print=false}
	{/if}
{else}
	{cycle name=postCycle values='3' print=false}
{/if}
	
{capture assign='messageClass'}message{if $this->getStyle()->getVariable('messages.framed')}Framed{/if}{@$this->getStyle()->getVariable('messages.sidebar.alignment')|ucfirst}{if $this->getStyle()->getVariable('messages.sidebar.divider.use')} dividers{/if}{/capture}
{capture assign='messageFooterClass'}messageFooter{@$this->getStyle()->getVariable('messages.footer.alignment')|ucfirst}{/capture}
	
{assign var="sidebar" value=$sidebarFactory->get('dummy', 0)}
{assign var="author" value=$sidebar->getUser()}
{assign var="messageID" value=0}				
{foreach from=$markings item=marking}
<div id="teamMarkingPreview{@$marking.groupID}" class="message"{if $marking.groupID != $markTeamMessageGroupID} style="display: none;"{/if}>
	<div class="messageInner {@$messageClass} container-{cycle name=postCycle}">										
		{include file='messageSidebar'}
					
		<div class="messageContent">
			<div class="messageContentInner color-{cycle name=messageCycle}">
				<div class="messageHeader">
					<p class="messageCount">
						<a href="#" class="messageNumber">1</a>
					</p>
					<div class="containerIcon">
						<img src="{icon}messageM.png{/icon}" alt="" />
					</div>
					<div class="containerContent">
						<p class="smallFont light">{@TIME_NOW|time}</p>
					</div>
				</div>
							
				<h3 class="messageTitle"><span>Lorem Ipsum</span></h3>
							
				<div class="messageBody">
					<div>
						Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.
					</div>
				</div>
																														
				<hr />
			</div>
		</div>
					
	</div>
</div>
{/foreach}			