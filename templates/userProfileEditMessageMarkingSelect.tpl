<script type="text/javascript">
	//<![CDATA[
		var markingIDs = new Array();
		{foreach from=$markings item=marking}
		markingIDs.push({$marking->markingID});
		{/foreach}

		function showMessageMarkingPreview(markingID) {
			markingIDs.each(function(id) {
				if (id != markingID) {
					$('messageMarkingPreview' + id).hide();
				}
				else {
					$('messageMarkingPreview' + id).show();
				}
			});
		}
	//]]>
</script>
<ul class="formOptionsLong">
	<li><label><input onclick="showMessageMarkingPreview(0)" type="radio" name="defaultMessageMarkingID" value="0" {if $defaultMessageMarkingID == 0}checked="checked" {/if}/> <span>{lang}wcf.message.marking.defaultMessageMarkingID.none{/lang}</span></label></li>
	{foreach from=$markings item=marking}
		<li><label><input onclick="showMessageMarkingPreview({@$marking->markingID})" type="radio" name="defaultMessageMarkingID" value="{@$marking->markingID}" {if $marking->markingID == $defaultMessageMarkingID}checked="checked" {/if}/> <span>{lang}{$marking->title}{/lang}</span></label></li>
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
<div id="messageMarkingPreview{@$marking->markingID}" class="message"{if $marking->markingID != $defaultMessageMarkingID} style="display: none;"{/if}>
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
				
				<div class="{@$messageFooterClass}">
					<div class="smallButtons">
						<ul>
							<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="{lang}wcf.global.scrollUp{/lang}" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>							
						</ul>
					</div>
				</div>																														
				<hr />
			</div>
		</div>
					
	</div>
</div>
{/foreach}