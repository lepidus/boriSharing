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
                {fbvFormSection title="plugins.generic.boriSharing.privacyTerms" list=true}
                    {translate key="plugins.generic.boriSharing.term"}    
                    {fbvElement type="checkbox" name="termsAccepted" id="termsCheckbox" value="1" checked=$termsAccepted label="plugins.generic.boriSharing.acceptTerms" translate=true}
                {/fbvFormSection}
			    {fbvFormButtons id="boriPluginSettingsFormSubmit" submitText="common.save" hideCancel=true}
			{else}
				{translate key="plugins.generic.boriSharing.alreadyAcceptedTerms"}
			{/if}
		{/fbvFormArea}
	</form>
</div>