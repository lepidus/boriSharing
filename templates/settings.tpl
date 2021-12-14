<script>
	$(function() {ldelim}
		$('#boriSettingsForm').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
	{rdelim});
</script>

<div id="plnSettings">
	<form class="pkp_form" id="boriSettingsForm" method="post" action="{url router=$smarty.const.ROUTE_COMPONENT op="manage" category="generic" plugin=$pluginName verb="settings" save=true}">
		{include file="controllers/notification/inPlaceNotification.tpl" notificationId="BoriSettingsFormNotification"}

		{fbvFormArea id="BoriSettingsFormArea"}
            {if not $termsAccepted}      
                {fbvFormSection title="plugins.generic.boriSharing.privacyTerms" }
                    <div style="text-align: justify;">{translate key="plugins.generic.boriSharing.term"}</div>
                    <label>
						<input type="checkbox" name="termsAccepted" id="termsCheckbox" value="1"/>
						{translate key="plugins.generic.boriSharing.acceptTerms"}
					</label>
                {/fbvFormSection}

				{fbvFormSection title="plugins.generic.boriSharing.authKey"}
                    <div>			
						{fbvFormSection label="plugins.generic.boriSharing.userAuthKey"}
							{fbvElement type="text" id="userAuthKey" required="true" value=$userAuthKey maxlength="32" size=$fbvStyles.size.MEDIUM}
						{/fbvFormSection}
					</div>
					<div>			
						{fbvFormSection label="plugins.generic.boriSharing.passwordAuthKey"}
							{fbvElement type="text" password=true required="true" id="passwordAuthKey" value=$passwordAuthKey maxlength="32" size=$fbvStyles.size.MEDIUM}
						{/fbvFormSection}
					</div>
                {/fbvFormSection}

			    {fbvFormButtons id="boriPluginSettingsFormSubmit" submitText="common.save" hideCancel=true}
			{else}
				{translate key="plugins.generic.boriSharing.alreadyAcceptedTerms"}
			{/if}
		{/fbvFormArea}
	</form>
</div>