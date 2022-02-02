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
						<input type="checkbox" name="termsAccepted" id="termsCheckbox" required="true" value="1"/>
						{translate key="plugins.generic.boriSharing.acceptTerms"}
					</label>
                {/fbvFormSection}
			{else}
				{translate key="plugins.generic.boriSharing.alreadyAcceptedTerms"}
			{/if}
			
			{fbvFormSection title="plugins.generic.boriSharing.authKey"}
				<div>			
					{fbvFormSection }
						{fbvElement type="text" id="userAuthKey" label="plugins.generic.boriSharing.userAuthKey" password="true" required="true" value=$userAuthKey maxlength="256" size=$fbvStyles.size.MEDIUM}
					{/fbvFormSection}
				</div>
			{/fbvFormSection}

			{fbvFormSection list="true" title="plugins.generic.boriSharing.API"}
				{fbvElement type="checkbox" id="disableAPI" label="plugins.generic.boriSharing.disableAPI" value="true" checked=$disableAPI|compare:true}
			{/fbvFormSection}

			{fbvFormButtons id="boriPluginSettingsFormSubmit" submitText="common.save" hideCancel=true}
		{/fbvFormArea}
	</form>
</div>