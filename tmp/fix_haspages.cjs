const fs = require('fs');
const path = require('path');

function walkDir(dir, callback) {
    fs.readdirSync(dir).forEach(f => {
        let dirPath = path.join(dir, f);
        let isDirectory = fs.statSync(dirPath).isDirectory();
        isDirectory ? walkDir(dirPath, callback) : callback(path.join(dir, f));
    });
}

// Update Blades (remove any remaining ->hasPages() checks)
let filesUpdated = 0;
walkDir('C:/xampp1/htdocs/MBG/resources/views', function(filePath) {
    if (filePath.endsWith('.blade.php')) {
        let content = fs.readFileSync(filePath, 'utf8');
        let original = content;
        
        // Match multiline @if($var->hasPages()) ... @endif blocks
        content = content.replace(/@if\s*\(\$.*?->hasPages\(\)\)[\s\S]*?@endif/g, '');
        
        if (original !== content) {
            fs.writeFileSync(filePath, content, 'utf8');
            console.log('Removed hasPages() from: ' + path.basename(filePath));
            filesUpdated++;
        }
    }
});
console.log('Total files cleaned: ' + filesUpdated);