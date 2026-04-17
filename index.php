<?php
// Data paths
$movies_json = 'data/movies.json';
$series_json = 'data/series.json';

// Fetching data
$movies = file_exists($movies_json) ? json_decode(file_get_contents($movies_json), true) : [];
$series = file_exists($series_json) ? json_decode(file_get_contents($series_json), true) : [];

// Merge and Sort by ID (Newest First)
$all_content = array_merge($movies ?: [], $series ?: []);
usort($all_content, function($a, $b) {
    return (int)$b['id'] <=> (int)$a['id'];
});

// Categories for filter
$categories = array_unique(array_column($all_content, 'category'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StreamX | Premium Entertainment</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap');
        body { background: #020202; color: white; font-family: 'Plus Jakarta Sans', sans-serif; overflow-x: hidden; }
        .glass-nav { background: rgba(0,0,0,0.85); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255,255,255,0.05); }
        .movie-card { transition: all 0.5s cubic-bezier(0.23, 1, 0.32, 1); }
        .movie-card:hover { transform: translateY(-10px) scale(1.03); z-index: 10; }
        .search-container { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); box-shadow: 0 4px 30px rgba(0, 0, 0, 0.5); }
        .neon-text { text-shadow: 0 0 10px rgba(229, 9, 20, 0.5); }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #050505; }
        ::-webkit-scrollbar-thumb { background: #222; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #e50914; }
    </style>
</head>
<body id="main-body">

    <nav class="glass-nav sticky top-0 z-[100] px-6 py-4 md:px-16 flex justify-between items-center">
        <div class="flex items-center gap-10">
            <h1 class="text-3xl font-black text-red-600 italic tracking-tighter neon-text">STREAMX</h1>
            <div class="hidden lg:flex gap-8 text-[10px] font-black uppercase tracking-[3px] text-gray-400">
                <a href="index.php" class="text-white border-b-2 border-red-600 pb-1">Home</a>
                <a href="movies.php" class="hover:text-white transition">Movies</a>
                <a href="series.php" class="hover:text-white transition">Web Series</a>
            </div>
        </div>
        <div class="flex items-center gap-6">
            <a href="admin.php" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center border border-white/10 hover:bg-red-600 transition">
                <i class="fa fa-user-cog text-sm"></i>
            </a>
        </div>
    </nav>

    <section class="px-6 md:px-16 mt-10">
        <div class="search-container p-2 rounded-[25px] flex flex-col md:flex-row gap-2">
            <div class="flex-1 relative">
                <i class="fa fa-search absolute left-5 top-1/2 -translate-y-1/2 text-gray-500"></i>
                <input type="text" id="mainSearch" placeholder="Type to search movies or series..." 
                       class="w-full bg-transparent py-4 pl-14 pr-6 rounded-[20px] outline-none transition border border-transparent focus:border-red-600">
            </div>
            <select id="catFilter" class="bg-zinc-900 px-6 py-4 rounded-[20px] outline-none border border-transparent focus:border-red-600 text-gray-400 font-bold text-sm">
                <option value="all">All Categories</option>
                <?php foreach($categories as $cat): ?>
                    <?php if(!empty($cat)): ?>
                        <option value="<?= htmlspecialchars($cat) ?>"><?= ucfirst(htmlspecialchars($cat)) ?></option>
                    <?php endif; ?>
                <?php endforeach; ?>
            </select>
        </div>
    </section>

    <main class="px-6 md:px-16 py-12">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-xl font-bold border-l-4 border-red-600 pl-4 uppercase tracking-widest">Recommended <span class="text-gray-500 font-light">For You</span></h2>
            <p id="resultCount" class="text-xs text-gray-500 font-bold uppercase tracking-tighter"></p>
        </div>

        <div id="contentGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-8">
            </div>

        <div id="noResults" class="hidden text-center py-32">
            <i class="fa fa-film text-6xl text-zinc-800 mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-600">No Content Found</h3>
            <p class="text-gray-500 mt-2">Humne koshish ki, par kuch mila nahi!</p>
        </div>
    </main>

    <footer class="py-20 text-center border-t border-white/5 mt-20">
        <p class="text-gray-500 text-[10px] tracking-[3px] uppercase mb-2">© 2026 STREAMX PREMIUM APP</p>
        <p class="text-gray-400 text-xs font-bold uppercase">
            Web App Developed By 
            <a href="https://t.me/maxmentor" class="text-red-600 hover:underline">@maxmentor</a>
        </p>
    </footer>

    <script>
        const allData = <?php echo json_encode($all_content); ?>;
        const grid = document.getElementById('contentGrid');
        const noResults = document.getElementById('noResults');
        const resultCount = document.getElementById('resultCount');

        function renderUI(items) {
            if (items.length === 0) {
                grid.innerHTML = '';
                noResults.classList.remove('hidden');
                resultCount.innerText = "0 Items Found";
                return;
            }
            noResults.classList.add('hidden');
            resultCount.innerText = items.length + " Items Found";

            grid.innerHTML = items.map(item => {
                const isSeries = item.type === 'series';
                const finalLink = isSeries ? `series_details.php?id=${item.id}` : `movie_details.php?id=${item.id}`;
                
                // Category Fallback Logic
                let displayCategory = item.category;
                if(!displayCategory || displayCategory.trim() === "" || displayCategory === "undefined") {
                    displayCategory = isSeries ? "Series" : "Movie";
                }

                return `
                <div class="movie-card group relative">
                    <a href="${finalLink}">
                        <div class="relative overflow-hidden rounded-[24px] shadow-2xl bg-zinc-900 aspect-[2/3]">
                            <img src="${item.poster}" alt="${item.name}" class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110" loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/20 to-transparent opacity-80 group-hover:opacity-100 transition-opacity"></div>
                            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-16 h-16 bg-red-600 rounded-full flex items-center justify-center shadow-2xl shadow-red-600/50 opacity-0 group-hover:opacity-100 scale-50 group-hover:scale-100 transition-all duration-500">
                                <i class="fa fa-play text-white text-xl"></i>
                            </div>
                            <div class="absolute top-4 left-4">
                                <span class="bg-black/60 backdrop-blur-md text-[9px] px-3 py-1 rounded-full font-black uppercase tracking-tighter border border-white/10">${displayCategory}</span>
                            </div>
                        </div>
                        <div class="mt-4 px-2">
                            <h3 class="font-bold text-sm truncate group-hover:text-red-500 transition-colors">${item.name}</h3>
                            <div class="flex items-center justify-between mt-1">
                                <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">${item.year || '2026'}</span>
                                <span class="text-[9px] ${isSeries ? 'text-blue-500' : 'text-green-500'} font-black uppercase">${item.type}</span>
                            </div>
                        </div>
                    </a>
                </div>`;
            }).join('');
        }

        function filterContent() {
            const searchTerm = document.getElementById('mainSearch').value.toLowerCase();
            const catTerm = document.getElementById('catFilter').value.toLowerCase();
            
            const filtered = allData.filter(item => {
                const name = (item.name || "").toLowerCase();
                const cat = (item.category || "").toLowerCase();
                const year = (item.year || "").toString();
                
                // Match search term with name, category OR year
                const matchesSearch = name.includes(searchTerm) || cat.includes(searchTerm) || year.includes(searchTerm);
                
                // Match category filter
                const matchesCat = (catTerm === 'all') || (cat === catTerm);
                
                return matchesSearch && matchesCat;
            });
            renderUI(filtered);
        }

        // Live typing search listener
        document.getElementById('mainSearch').addEventListener('input', filterContent);
        document.getElementById('catFilter').addEventListener('change', filterContent);
        
        // Initial Page Load
        window.onload = () => renderUI(allData);
    </script>
</body>
</html>
