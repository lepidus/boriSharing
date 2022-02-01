
function testExistTextBoxToAuth() {
    cy.get('input[id^=userAuthKey]').should('exist');
}

function testAcceptPrivacyTermsWithoutAuth() {
    cy.get('#termsCheckbox').check();
    cy.get('button[id^=submitFormButton]').contains('Save').click();
    cy.get('.pkp_helpers_half > .error').contains('This field is required.').should('exist');
}

function testAcceptPrivacyTermsWithAuth() {
    cy.get('input[id^=userAuthKey]').click();
    cy.get('input[id^=userAuthKey]').type( Cypress.env('UserAuthKey'), { delay: 1 });
    cy.get('button[id^=submitFormButton]').contains('Save').click();
    cy.get('.pkpNotification').contains('Plugin working').should('exist');
}


describe('BoriSharing Plugin auth key test', function() {
    it('Adds auth key tests', function() {
        cy.visit(Cypress.env('baseUrl') + 'index.php/anphlac/management/settings/website');
        cy.get('input[id=username]').click();
        cy.get('input[id=username]').type(Cypress.env('OJSAdminUsername'), { delay: 0 });
        cy.get('input[id=password]').click();
        cy.get('input[id=password]').type(Cypress.env('OJSAdminPassword'), { delay: 0 });
        cy.get('button[class=submit]').click();

        cy.get('#plugins-button').click();
        cy.get('#component-grid-settings-plugins-settingsplugingrid-category-generic-row-borisharingplugin > .first_column > .show_extras').click();
        cy.get('a[id^=component-grid-settings-plugins-settingsplugingrid-category-generic-row-borisharingplugin-settings-button]').click();

        testExistTextBoxToAuth();
        testAcceptPrivacyTermsWithoutAuth();
        testAcceptPrivacyTermsWithAuth();

    });

});


