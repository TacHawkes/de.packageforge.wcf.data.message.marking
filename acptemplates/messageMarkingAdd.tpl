{include file='header'}

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/messageMarking{@$action|ucfirst}L.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.message.marking.{@$action}{/lang}</h2>
	</div>
</div>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset}
	<p class="success">{lang}wcf.acp.message.marking.{@$action}.success{/lang}</p>
{/if}

<p class="info">{lang}wcf.acp.message.marking.intro{/lang}</p>

<div class="contentHeader">
	<div class="largeButtons">
		<ul><li><a href="index.php?page=MessageMarkingList&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/messageMarkingM.png" alt="" title="{lang}wcf.acp.menu.link.messageMarking.view{/lang}" /> <span>{lang}wcf.acp.menu.link.messageMarking.view{/lang}</span></a></li></ul>
	</div>
</div>
<form method="post" action="index.php?form=MessageMarking{@$action|ucfirst}">
	<div class="border content">
		<div class="container-1">		
			<fieldset>
				<legend>{lang}wcf.acp.message.marking.general{/lang}</legend>

				<div class="formElement{if $errorField == 'title'} formError{/if}" id="titleDiv">
					<div class="formFieldLabel">
						<label for="title">{lang}wcf.acp.message.marking.title{/lang}</label>
					</div>								
					<div class="formField">
						<input type="text" class="inputText" name="title" id="title" value="{$title}" />
						{if $errorField == 'title'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="titleHelpMessage">
						<p>{lang}wcf.acp.message.marking.title.description{/lang}</p>
					</div>
				</div>
				
				<script type="text/javascript">
				//<![CDATA[
					inlineHelp.register('title');
				//]]>
				</script>

				<div class="formElement" id="cssDiv">
					<div class="formFieldLabel">
						<label for="css">{lang}wcf.acp.message.marking.css{/lang}</label>
					</div>										
					<div class="formField">						
						<textarea id="css" name="css" rows="5" cols="60">{$css}</textarea>						
					</div>
					<div class="formFieldDesc hidden" id="cssHelpMessage">
						<p>{lang}wcf.acp.message.marking.css.description{/lang}</p>
					</div>
				</div>
				<script type="text/javascript">
				//<![CDATA[
					inlineHelp.register('css');
				//]]>
				</script>
			</fieldset>

			<fieldset>
				<legend>{lang}wcf.acp.message.marking.permissions{/lang}</legend>
					<div class="formElement">
						<div class="formFieldLabel">
							<label>{lang}wcf.acp.message.marking.groupIDs{/lang}</label>
						</div>
						<div class="formField">
							{htmlcheckboxes options=$groupSelect selected=$groupIDs name=groupIDs}
						</div>
						<div class="formFieldDesc hidden" id="groupIDsHelpMessage">
							<p>{lang}wcf.acp.message.marking.groupIDs.description{/lang}</p>
						</div>
					</div>
					<script type="text/javascript">
					//<![CDATA[
						inlineHelp.register('groupIDs');
					//]]>
					</script>
			</fieldset>

			{if $additionalFields|isset}{@$additionalFields}{/if}
		</div>
	</div>

	<div class="formSubmit">
		<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
		<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
		<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
 		{@SID_INPUT_TAG}
 		{if $markingID|isset}<input type="hidden" name="markingID" value="{@$markingID}" />{/if}
 	</div>
</form>

{include file='footer'}