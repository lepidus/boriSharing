
function testExistsAuthKeyInput() {
    cy.get('input[id^=userAuthKey]').should('exist');
}

function testEditAuthKey() {
    cy.get('input[id^=userAuthKey]').click();
    cy.get('input[id^=userAuthKey]').type( Cypress.env('UserAuthKey'), { delay: 1 });
    cy.get('button[id^=submitFormButton]').contains('Save').click();
    cy.get('.pkpNotification').contains('Authentication key added/edited').should('exist');
}

describe('BoriSharing Plugin edit auth key', function() {
    it('Auth key editing tests', function() {
        cy.visit(Cypress.env('baseUrl') + 'index.php/h/management/settings/website');
        cy.get('input[id=username]').click();
        cy.get('input[id=username]').type(Cypress.env('OJSAdminUsername'), { delay: 0 });
        cy.get('input[id=password]').click();
        cy.get('input[id=password]').type(Cypress.env('OJSAdminPassword'), { delay: 0 });
        cy.get('button[class=submit]').click();

        cy.get('#plugins-button').click();
        cy.get('#component-grid-settings-plugins-settingsplugingrid-category-generic-row-borisharingplugin > .first_column > .show_extras').click();        
        cy.get('a[id^=component-grid-settings-plugins-settingsplugingrid-category-generic-row-borisharingplugin-settings-button]').click();

        testExistsAuthKeyInput();
        testEditAuthKey();

    });

});