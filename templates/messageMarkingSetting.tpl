						<div class="formElement">
							<div class="formFieldLabel">
								<label for="markingID">{lang}wcf.message.marking.markingID{/lang}</label>
							</div>
							<div class="formField">
								<select name="markingID" id="markingID">
									{foreach from=$availableMarkings item=availableMarking}
									<option value="{@$availableMarking->markingID}"
										{if $availableMarking->markingID == $markingID} selected="selected"{/if}>{lang}{$marking->title}{/lang}</option>
									{/foreach}
								</select>
							</div>
							<div class="formFieldDesc">
								{lang}wcf.message.marking.markingID.description{/lang}
							</div>
						</div>