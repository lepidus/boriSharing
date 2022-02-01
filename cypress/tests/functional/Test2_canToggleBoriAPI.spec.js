
function testExistCheckboxToToggleAPIUse() {
    cy.get('#disableAPI').should('exist');
}

function testDisableAPI(){
    cy.get('#disableAPI').check();
    cy.get('button[id^=submitFormButton]').contains('Save').click();
    cy.get('.pkpNotification').contains('Disabled API').should('exist');
}

function testEnableAPI(){
    cy.get('a[id^=component-grid-settings-plugins-settingsplugingrid-category-generic-row-borisharingplugin-settings-button]').click();
    cy.get('#disableAPI').uncheck();
    cy.get('button[id^=submitFormButton]').contains('Save').click();
    cy.get('.pkpNotification').contains('Enabled API').should('exist');
}

describe('BoriSharing Plugin enable/disable API test', function() {
    it('API toggle tests', function() {
        cy.visit(Cypress.env('baseUrl') + 'index.php/h/management/settings/website');
        cy.get('input[id=username]').click();
        cy.get('input[id=username]').type(Cypress.env('OJSAdminUsername'), { delay: 0 });
        cy.get('input[id=password]').click();
        cy.get('input[id=password]').type(Cypress.env('OJSAdminPassword'), { delay: 0 });
        cy.get('button[class=submit]').click();

        cy.get('#plugins-button').click();
        cy.get('#component-grid-settings-plugins-settingsplugingrid-category-generic-row-borisharingplugin > .first_column > .show_extras').click();        
        cy.get('a[id^=component-grid-settings-plugins-settingsplugingrid-category-generic-row-borisharingplugin-settings-button]').click();

        testExistCheckboxToToggleAPIUse();
        testDisableAPI();
        testEnableAPI()

    });

});


