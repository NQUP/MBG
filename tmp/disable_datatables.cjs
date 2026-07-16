const fs = require('fs');
const path = require('path');

function walkDir(dir, callback) {
    fs.readdirSync(dir).forEach(f => {
        let dirPath = path.join(dir, f);
        let isDirectory = fs.statSync(dirPath).isDirectory();
        isDirectory ? walkDir(dirPath, callback) : callback(path.join(dir, f));
    });
}

walkDir('C:/xampp1/htdocs/MBG/resources/views', function(filePath) {
    if (filePath.endsWith('.blade.php')) {
        let content = fs.readFileSync(filePath, 'utf8');
        let original = content;
        
        // Add no-datatable class to form tables or summary tables
        // Usually these are in create/edit views or have specific classes
        if (filePath.includes('create.blade.php') || filePath.includes('edit.blade.php') || filePath.includes('show.blade.php')) {
            // Replace <table class="table mb-0" id="tabelDetail">
            content = content.replace(/<table class="table/g, '<table class="table no-datatable');
        }
        
        if (original !== content) {
            fs.writeFileSync(filePath, content, 'utf8');
            console.log('Disabled DataTables on: ' + path.basename(filePath));
        }
    }
});