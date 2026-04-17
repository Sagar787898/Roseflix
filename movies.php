<?php
// Data fetch karna
$movies = json_decode(file_get_contents('data/movies.json'), true) ?: [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Movies | Premium Stream</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #080808; color: #eee; font-family: 'Inter', sans-serif; }
        .glass-card { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); }
        .movie-hover:hover { transform: translateY(-8px); transition: 0.3s ease; border-color: #e50914; }
    </style>
</head>
<body>
    <header class="p-6 flex flex-col md:flex-row justify-between items-center gap-4 sticky top-0 bg-[#080808]/90 z-50 backdrop-blur-md">
        <a href="index.php" class="text-3xl font-extrabold text-red-600 tracking-tighter">STREAM<span class="text-white">X</span></a>
        
        <div class="flex gap-2 w-full md:w-auto">
            <input type="text" id="movieSearch" placeholder="Search Movies..." class="bg-zinc-900 border border-zinc-700 px-4 py-2 rounded-full w-full md:w-80 focus:outline-none focus:border-red-600">
        </div>
    </header>

    <main class="p-6">
        <h2 class="text-xl font-bold mb-6 border-l-4 border-red-600 pl-3">Explore Movies</h2>
        
        <div id="movieGrid" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
            <?php foreach($movies as $m): ?>
            <div class="movie-hover glass-card rounded-xl overflow-hidden cursor-pointer" onclick="location.href='player.php?url=<?= base64_encode($m['stream_link']) ?>'">
                <div class="relative group">
                    <img src="<?= $m['poster'] ?>" alt="<?= $m['name'] ?>" class="w-full h-72 object-cover">
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                        <span class="bg-red-600 p-3 rounded-full">▶</span>
                    </div>
                </div>
                <div class="p-3">
                    <h3 class="font-semibold text-sm truncate"><?= $m['name'] ?></h3>
                    <div class="flex justify-between items-center mt-2 text-[10px] uppercase tracking-widest text-gray-400">
                        <span><?= $m['category'] ?></span>
                        <span class="bg-zinc-800 px-2 py-1 rounded text-white"><?= $m['year'] ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</body>
</html>