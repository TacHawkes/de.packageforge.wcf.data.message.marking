{include file='header'}
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/messageMarkingL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.message.marking.view{/lang}</h2>
	</div>
</div>

{if $deletedMarkingID}
	<p class="success">{lang}wcf.acp.message.marking.delete.success{/lang}</p>
{/if}

<div class="contentHeader">
	{pages print=true assign=pagesLinks link="index.php?page=MessageMarkingList&pageNo=%d&sortField=$sortField&sortOrder=$sortOrder&packageID="|concat:PACKAGE_ID:SID_ARG_2ND_NOT_ENCODED}

	{if $this->user->getPermission('admin.display.canAddMessageMarking')}
		<div class="largeButtons">
			<ul><li><a href="index.php?form=MessageMarkingAdd&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/messageMarkingAddM.png" alt="" title="{lang}wcf.acp.message.marking.add{/lang}" /> <span>{lang}wcf.acp.message.marking.add{/lang}</span></a></li></ul>
		</div>
	{/if}
</div>

{if $markings|count}
	<div class="border titleBarPanel">
		<div class="containerHead"><h3>{lang}wcf.acp.message.marking.view.count{/lang}</h3></div>
	</div>
	<div class="border borderMarginRemove">
		<table class="tableList">
			<thead>
				<tr class="tableHead">
					<th class="columnMessageMarkingID{if $sortField == 'markingID'} active{/if}" colspan="2"><div><a href="index.php?page=MessageMarkingList&amp;pageNo={@$pageNo}&amp;sortField=markingID&amp;sortOrder={if $sortField == 'markingID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.message.marking.markingID{/lang}{if $sortField == 'markingID'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					<th class="columnMessageMarkingTitle{if $sortField == 'title'} active{/if}"><div><a href="index.php?page=MessageMarkingList&amp;pageNo={@$pageNo}&amp;sortField=title&amp;sortOrder={if $sortField == 'title' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.message.marking.title{/lang}{if $sortField == 'title'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>									

					{if $additionalColumnHeads|isset}{@$additionalColumnHeads}{/if}
				</tr>
			</thead>
			<tbody>
			{foreach from=$markings item=messageMarking}
				<tr class="{cycle values="container-1,container-2"}">
					<td class="columnIcon">
						{if $this->user->getPermission('admin.display.canEditMessageMarking')}
							{if $messageMarking->disabled}
								<a href="index.php?action=MessageMarkingEnable&amp;markingID={@$messageMarking->markingID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/disabledS.png" alt="" title="{lang}wcf.acp.message.marking.enable{/lang}" /></a>
							{else}
								<a href="index.php?action=MessageMarkingDisable&amp;markingID={@$messageMarking->markingID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/enabledS.png" alt="" title="{lang}wcf.acp.message.marking.disable{/lang}" /></a>
							{/if}
						{else}
							{if $messageMarking->disabled}
								<img src="{@RELATIVE_WCF_DIR}icon/disabledDisabledS.png" alt="" title="{lang}wcf.acp.message.marking.enable{/lang}" />
							{else}
								<img src="{@RELATIVE_WCF_DIR}icon/enabledDisabledS.png" alt="" title="{lang}wcf.acp.message.marking.disable{/lang}" />
							{/if}
						{/if}

						{if $this->user->getPermission('admin.display.canEditMessageMarking')}
							<a href="index.php?form=MessageMarkingEdit&amp;markingID={@$messageMarking->markingID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/editS.png" alt="" title="{lang}wcf.acp.message.marking.edit{/lang}" /></a>
						{else}
							<img src="{@RELATIVE_WCF_DIR}icon/editDisabledS.png" alt="" title="{lang}wcf.acp.message.marking.edit{/lang}" />
						{/if}

						{if $this->user->getPermission('admin.display.canDeleteMessageMarking')}
							<a onclick="return confirm('{lang}wcf.acp.message.marking.delete.sure{/lang}')" href="index.php?action=MessageMarkingDelete&amp;markingID={@$messageMarking->markingID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" title="{lang}wcf.acp.message.marking.delete{/lang}" /></a>
						{else}
							<img src="{@RELATIVE_WCF_DIR}icon/deleteDisabledS.png" alt="" title="{lang}wcf.acp.message.marking.delete{/lang}" />
						{/if}

						{if $additionalButtons.$messageMarking->markingID|isset}{@$additionalButtons.$messageMarking->markingID}{/if}
					</td>
					<td class="columnMessageMarkingID columnID">{@$messageMarking->markingID}</td>
					<td class="columnMessageMarkingTitle columnText">
						{if $this->user->getPermission('admin.display.canEditMessageMarking')}
							<a href="index.php?form=MessageMarkingEdit&amp;markingID={@$messageMarking->markingID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.message.marking.edit{/lang}">{lang}{$messageMarking->title}{/lang}</a>
						{else}
							{lang}{$messageMarking->title}{/lang}
						{/if}
					</td>					

					{if $additionalColumns.$messageMarking->markingID|isset}{@$additionalColumns.$messageMarking->markingID}{/if}
				</tr>
			{/foreach}
			</tbody>
		</table>
	</div>

	<div class="contentFooter">
		{@$pagesLinks}

		{if $this->user->getPermission('admin.display.canAddMessageMarking')}
			<div class="largeButtons">
				<ul><li><a href="index.php?form=MessageMarkingAdd&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/messageMarkingAddM.png" alt="" title="{lang}wcf.acp.message.marking.add{/lang}" /> <span>{lang}wcf.acp.message.marking.add{/lang}</span></a></li></ul>
			</div>
		{/if}
	</div>
{else}
	<div class="border content">
		<div class="container-1">
			<p>{lang}wcf.acp.message.marking.view.count.noEntries{/lang}</p>
		</div>
	</div>
{/if}

{include file='footer'}