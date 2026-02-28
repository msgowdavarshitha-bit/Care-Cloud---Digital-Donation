const path = require('path');
const fs = require('fs');

module.exports = function(app) {
    // Custom render function to use layout
    app.locals.layout = function(view, locals = {}) {
        return new Promise((resolve, reject) => {
            // Read the layout file
            const layoutPath = path.join(__dirname, 'layout.ejs');
            const viewPath = path.join(__dirname, view + '.ejs');
            
            fs.readFile(layoutPath, 'utf8', (err, layoutData) => {
                if (err) {
                    return reject(err);
                }
                
                fs.readFile(viewPath, 'utf8', (err, viewData) => {
                    if (err) {
                        return reject(err);
                    }
                    
                    // Replace the body placeholder with the view content
                    const renderedPage = layoutData.replace('<%- body %>', viewData);
                    
                    // Process any EJS-like template variables in the rendered page
                    let processedPage = renderedPage;
                    
                    // Handle title variable
                    if (locals.title) {
                        processedPage = processedPage.replace('<%= title %>', locals.title);
                    }
                    
                    // Handle user variable in navigation
                    if (typeof locals.user !== 'undefined') {
                        if (locals.user) {
                            processedPage = processedPage.replace('<% if(user) { %>', locals.user ? '' : '<!--');
                            processedPage = processedPage.replace('<% } else { %>', locals.user ? '-->' : '');
                            processedPage = processedPage.replace('<% } %>', locals.user ? '' : '-->');
                        } else {
                            processedPage = processedPage.replace('<% if(user) { %>', locals.user ? '' : '<!--');
                            processedPage = processedPage.replace('<% } else { %>', locals.user ? '-->' : '');
                            processedPage = processedPage.replace('<% } %>', locals.user ? '' : '-->');
                        }
                    }
                    
                    resolve(processedPage);
                });
            });
        });
    };
};