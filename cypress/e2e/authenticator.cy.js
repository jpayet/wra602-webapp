describe('Formulaire d\'inscription', () => {
    it('test 1 - inscription OK', () => {
        cy.visit('http://127.0.0.1:37385/register');

        // Définir un mail unique pour chaque test
        const timestamp = Date.now();
        const email = `test${timestamp}@register.com`;

        // Entrer un nom d'utilisateur et un mot de passe
        cy.get('#registration_form_email').type(email);
        cy.get('#registration_form_plainPassword').type('testregister1234');

        // Soumettre le formulaire
        cy.get('button[type="submit"]').click();

        // Vérifier que l'utilisateur est connecté
        cy.contains('Bienvenue sur PDF Raptor').should('exist');
    });

    it('test 2 - inscription KO', () => {
        cy.visit('http://127.0.0.1:37385/register');

        // Entrer un nom d'utilisateur qui existe déjà
        cy.get('#registration_form_email').type('jpayet.e2@gmail.com');
        cy.get('#registration_form_plainPassword').type('password');

        // Soumettre le formulaire
        cy.get('button[type="submit"]').click();

        // Vérifier que le message d'erreur est affiché
        cy.contains('Il existe déjà un compte avec cet email.').should('exist');
    });
});