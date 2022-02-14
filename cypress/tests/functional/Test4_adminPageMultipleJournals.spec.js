function loginAdminUser() {
    cy.get('input[id=username]').click();
        cy.get('input[id=username]').type(Cypress.env('OJSAdminUsername'), { delay: 0 });
        cy.get('input[id=password]').click();
        cy.get('input[id=password]').type(Cypress.env('OJSAdminPassword'), { delay: 0 });
        cy.get('button[class=submit]').click();
}

function hasMoreThanOneJournal() {
    cy.get('table[id^=component-grid-admin-context-contextgrid] > tbody > tr:visible').its('length').should('be.gte', 2);
}

describe('BoriSharing Plugin access admin page', function() {
    it('Access admin page when having more than one journal', function() {
        cy.visit(Cypress.env('baseUrl') + 'index.php/index/admin');
        loginAdminUser();

        cy.visit(Cypress.env('baseUrl') + 'index.php/index/admin/contexts');
        hasMoreThanOneJournal();
    });
});