
function testExistCheckboxToAPIWorking() {
    cy.get('#disableAPI').should('exist');
}

function testExistNotificationToUser(){
    cy.get('#disableAPI').check();
    cy.get('button[id^=submitFormButton]').contains('Save').click();
    cy.get('.app__notifications > :nth-child(1) > :nth-child(1)').contains('Terms accepted successfully').should('exist');
    cy.get('.app__notifications > :nth-child(1) > :nth-child(2)').contains('Disabled API').should('exist');
    cy.get('.app__notifications > :nth-child(1) > :nth-child(3)').contains('Plugin working').should('exist');
}

function testEnableAPI(){
    cy.get('a[id^=component-grid-settings-plugins-settingsplugingrid-category-generic-row-borisharingplugin-settings-button]').click();
    cy.get('#disableAPI').uncheck();
    cy.get('button[id^=submitFormButton]').contains('Save').click();
    cy.get('.app__notifications > :nth-child(1) > :nth-child(4)').contains('Enabled API').should('exist');
}

describe('BoriSharing Plugin auth key test', function() {
    it('Adds turn off API tests', function() {
        cy.visit(Cypress.env('baseUrl') + 'index.php/h/submissions');
        cy.get('input[id=username]').click();
        cy.get('input[id=username]').type(Cypress.env('OJSAdminUsername'), { delay: 0 });
        cy.get('input[id=password]').click();
        cy.get('input[id=password]').type(Cypress.env('OJSAdminPassword'), { delay: 0 });
        cy.get('button[class=submit]').click();

        cy.get(':nth-child(3) > ul > :nth-child(2) > .app__navItem').click();
        cy.get('#plugins-button').click();
        cy.get('#component-grid-settings-plugins-settingsplugingrid-category-generic-row-borisharingplugin > .first_column > .show_extras').click();
        cy.get('a[id^=component-grid-settings-plugins-settingsplugingrid-category-generic-row-borisharingplugin-settings-button]').click();
        
        cy.get('#termsCheckbox').check();
        cy.get('input[id^=userAuthKey]').click();
        cy.get('input[id^=userAuthKey]').type( Cypress.env('UserAuthKey'), { delay: 2 });
        
        testExistCheckboxToAPIWorking();

        testExistNotificationToUser();

        testEnableAPI()

    });

});


