describe('Formulaire de Connexion', () => {
    it('test 1 - connexion OK', () => {
        cy.visit('http://127.0.0.1:37385/login');

        // Entrer le nom d'utilisateur et le mot de passe
        cy.get('#username').type('jpayet.e2@gmail.com');
        cy.get('#password').type('testest');

        // Soumettre le formulaire
        cy.get('button[type="submit"]').click();

        // Vérifier que l'utilisateur est connecté
        cy.contains('Bienvenue sur PDF Raptor').should('exist');
    });

    it('test 2 - connexion KO', () => {
        cy.visit('http://127.0.0.1:37385/login');

        // Entrer un nom d'utilisateur et un mot de passe incorrects
        cy.get('#username').type('jpayet.e2@gmail.com');
        cy.get('#password').type('password');

        // Soumettre le formulaire
        cy.get('button[type="submit"]').click();

        // Vérifier que le message d'erreur est affiché
        cy.contains('Invalid credentials.').should('exist');
    });
});