const fs = require('fs');
const path = require('path');

const { defineConfig } = require("cypress");

module.exports = defineConfig({
  e2e: {
    setupNodeEvents(on, config) {
      on('task', {
        checkFileExists({ directory, regex }) {
          return fs.promises.readdir(directory)
              .then(files => files.some(file => new RegExp(regex).test(file)))
              .catch(() => false);
        },
        deleteFiles(directory) {
          return fs.promises.readdir(directory)
              .then(files => Promise.all(files.map(file => fs.promises.unlink(path.join(directory, file)))))
              .catch(error => console.error(`Error deleting files:`, error));
        },
      });
    },
  },
});