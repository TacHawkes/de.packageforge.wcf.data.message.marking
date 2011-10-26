						<div class="formElement">
							<div class="formFieldLabel">
								<label for="markingID">{lang}wcf.message.marking.markingID{/lang}</label>
							</div>
							<div class="formField">
								<select name="markingID" id="markingID">
									<option value="0"{if $markingID == 0} selected="selected"{/if}>{lang}wcf.message.marking.markingID.none{/lang}</option>
									{foreach from=$availableMarkings item=availableMarking}
									<option value="{@$availableMarking->markingID}"
										{if $availableMarking->markingID == $markingID} selected="selected"{/if}>{lang}{$availableMarking->title}{/lang}</option>
									{/foreach}
								</select>
							</div>
							<div class="formFieldDesc">
								{lang}wcf.message.marking.markingID.description{/lang}
							</div>
						</div>