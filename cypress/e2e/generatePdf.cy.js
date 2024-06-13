describe('Générer un pdf', () => {
    it('test 1 - générer un pdf avec une url', () => {
        // Se connecter
        cy.visit('http://127.0.0.1:37385/login');
        cy.get('#username').type('jpayet.e2@gmail.com');
        cy.get('#password').type('testest');
        cy.get('button[type="submit"]').click();
        cy.contains('Bienvenue sur PDF Raptor').should('exist');

        // Se rendre sur la page de génération de pdf avec une url
        cy.visit('http://127.0.0.1:37385/pdf/generate/url');

        // Entrer une url
        cy.get('#form_url').type('https://www.google.com');

        // Soumettre le formulaire
        cy.get('button[type="submit"]').click();

        // Vérifier qu'un fichier pdf a bien été téléchargé
        cy.wait(2000);
        cy.task('checkFileExists', {
            directory: '/home/jpayet/symfony/www/wra602-webapp/cypress/downloads',
            regex: '.*\\.pdf$'
        }).then((exists) => {
            expect(exists).to.be.true;
        });

        // Vider le dossier de téléchargement
        cy.task('deleteFiles', '/home/jpayet/symfony/www/wra602-webapp/cypress/downloads');
    });

    it('test 2 - générer un pdf avec un fichier html', () => {
        // Se connecter
        cy.visit('http://127.0.0.1:37385/login');
        cy.get('#username').type('jpayet.e2@gmail.com');
        cy.get('#password').type('testest');
        cy.get('button[type="submit"]').click();
        cy.contains('Bienvenue sur PDF Raptor').should('exist');

        // Se rendre sur la page de génération de pdf avec un fichier html
        cy.visit('http://127.0.0.1:37385/pdf/generate/html');

        // Uploader un fichier html
        cy.fixture('html/upload_file.html', 'binary')
            .then(Cypress.Blob.binaryStringToBlob)
            .then(fileContent => {
                cy.get('#form_file').attachFile({
                    fileContent,
                    fileName: 'upload_file.html',
                    mimeType: 'text/html',
                    encoding: 'utf-8'
                });
            });

        // Soumettre le formulaire
        cy.get('button[type="submit"]').click();

        // Vérifier qu'un fichier pdf a bien été téléchargé
        cy.wait(2000);
        cy.task('checkFileExists', {
            directory: '/home/jpayet/symfony/www/wra602-webapp/cypress/downloads',
            regex: '.*\\.pdf$'
        }).then((exists) => {
            expect(exists).to.be.true;
        });

        // Vider le dossier de téléchargement
        cy.task('deleteFiles', '/home/jpayet/symfony/www/wra602-webapp/cypress/downloads');
    });
});