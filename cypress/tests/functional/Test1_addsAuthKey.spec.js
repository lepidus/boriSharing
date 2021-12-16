
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
    cy.get('input[id^=userAuthKey]').type( '7815696ecbf1c96e6894b779456d330e', { delay: 0 });
    cy.get('button[id^=submitFormButton]').contains('Save').click();
    cy.get('.app__notifications > :nth-child(1) > :nth-child(2)').contains('Plugin working').should('exist');
}


describe('BoriSharing Plugin auth key test', function() {
    it('Adds auth key tests', function() {
        cy.visit(Cypress.env('baseUrl') + 'index.php/f/submissions');
        cy.get('input[id=username]').click();
        cy.get('input[id=username]').type(Cypress.env('OJSAdminUsername'), { delay: 0 });
        cy.get('input[id=password]').click();
        cy.get('input[id=password]').type(Cypress.env('OJSAdminPassword'), { delay: 0 });
        cy.get('button[class=submit]').click();

        cy.get(':nth-child(3) > ul > :nth-child(2) > .app__navItem').click();
        cy.get('#plugins-button').click();
        cy.get('#component-grid-settings-plugins-settingsplugingrid-category-generic-row-borisharingplugin > .first_column > .show_extras').click();
        cy.get('a[id^=component-grid-settings-plugins-settingsplugingrid-category-generic-row-borisharingplugin-settings-button]').click();

        testExistTextBoxToAuth();
        testAcceptPrivacyTermsWithoutAuth();
        testAcceptPrivacyTermsWithAuth();

    });

});


