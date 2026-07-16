const fs = require('fs');
const path = require('path');

function walkDir(dir, callback) {
    fs.readdirSync(dir).forEach(f => {
        let dirPath = path.join(dir, f);
        let isDirectory = fs.statSync(dirPath).isDirectory();
        isDirectory ? walkDir(dirPath, callback) : callback(path.join(dir, f));
    });
}

// 1. Update Controllers
walkDir('C:/xampp1/htdocs/MBG/app/Http/Controllers', function(filePath) {
    if (filePath.endsWith('.php')) {
        let content = fs.readFileSync(filePath, 'utf8');
        let original = content;
        
        // Replace ->paginate(15) or ->paginate(20) with ->get()
        content = content.replace(/->paginate\(\d+\)/g, '->get()');
        
        // Replace ->withQueryString() if it exists after get()
        content = content.replace(/->get\(\)->withQueryString\(\)/g, '->get()');
        
        if (original !== content) {
            fs.writeFileSync(filePath, content, 'utf8');
            console.log('Updated Controller: ' + path.basename(filePath));
        }
    }
});

// 2. Update Blades (remove ->links())
walkDir('C:/xampp1/htdocs/MBG/resources/views', function(filePath) {
    if (filePath.endsWith('.blade.php')) {
        let content = fs.readFileSync(filePath, 'utf8');
        let original = content;
        
        // Remove pagination links block
        // Example: @if($satuans->hasPages())<div class="card-footer">{{ $satuans->links() }}</div>@endif
        content = content.replace(/@if\(\$.*?->hasPages\(\)\).*?links\(\).*?@endif/g, '');
        
        // Example: <div class="card-footer">{{ $produksis->links() }}</div>
        content = content.replace(/<div class="card-footer">\s*\{\{\s*\$.*?->links\(\)\s*\}\}\s*<\/div>/g, '');
        
        // Just in case it's on multiple lines
        content = content.replace(/\{\{\s*\$[a-zA-Z0-9_]+->links\(\)\s*\}\}/g, '');
        
        if (original !== content) {
            fs.writeFileSync(filePath, content, 'utf8');
            console.log('Updated Blade: ' + path.basename(filePath));
        }
    }
});