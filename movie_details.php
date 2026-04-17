<?php
$id = $_GET['id'] ?? '';
$movies_list = json_decode(file_get_contents('data/movies.json'), true) ?: [];

// Movie find karne ka logic
$movie = null;
foreach($movies_list as $m) {
    if($m['id'] == $id) { $movie = $m; break; }
}

if(!$movie) { die("<h1 style='color:white;text-align:center;margin-top:50px;'>Movie Not Found!</h1>"); }

// Helper function for Base64 (Player compatibility)
function b64($url) {
    return base64_encode($url);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $movie['name'] ?> | Premium Movie</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap');
        body { background: #020202; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .hero-banner { 
            background: linear-gradient(to bottom, rgba(2,2,2,0.1), #020202), url('<?= $movie['poster'] ?>');
            background-size: cover; background-position: center;
        }

        .action-btn { 
            background: rgba(255,255,255,0.05); 
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1);
            transition: 0.4s;
        }

        .action-btn:hover { 
            background: #e50914; 
            border-color: #e50914; 
            transform: scale(1.05);
            box-shadow: 0 0 30px rgba(229, 9, 20, 0.4);
        }

        .glass-card {
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 30px;
        }

        .glow-text {
            text-shadow: 0 0 20px rgba(255,255,255,0.2);
        }
    </style>
</head>
<body>

    <div class="fixed top-0 left-0 w-full z-[100] p-6 md:px-16 flex justify-between items-center bg-gradient-to-b from-black/80 to-transparent">
        <a href="index.php" class="w-10 h-10 md:w-12 md:h-12 rounded-full flex items-center justify-center action-btn">
            <i class="fa fa-arrow-left"></i>
        </a>
        <div class="flex gap-4">
             <span class="bg-red-600 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest">Premium</span>
        </div>
    </div>

    <div class="hero-banner h-[70vh] md:h-[85vh] flex flex-col justify-end p-6 md:px-16 pb-12 md:pb-20 relative">
        <div class="max-w-4xl animate-fade-in">
            <div class="flex flex-wrap items-center gap-3 md:gap-4 mb-4 md:mb-6">
                <span class="text-yellow-500 font-bold text-sm md:text-base"><i class="fa fa-star"></i> <?= $movie['rating'] ?? 'N/A' ?></span>
                <span class="w-1 h-1 bg-gray-600 rounded-full"></span>
                <span class="text-gray-300 font-bold uppercase tracking-widest text-[10px] md:text-sm"><?= $movie['category'] ?></span>
                <span class="w-1 h-1 bg-gray-600 rounded-full"></span>
                <span class="text-gray-300 font-bold text-[10px] md:text-sm"><?= $movie['year'] ?></span>
            </div>

            <h1 class="text-4xl md:text-8xl font-black mb-6 md:mb-8 uppercase italic tracking-tighter glow-text leading-[1] md:leading-[0.9]">
                <?= $movie['name'] ?>
            </h1>

            <div class="flex flex-wrap gap-3 md:gap-4 mt-6 md:mt-10">
                <a href="player.php?url=<?= b64($movie['stream_link']) ?>" class="flex-1 md:flex-none text-center px-6 md:px-10 py-4 md:py-5 bg-red-600 rounded-2xl font-black text-xs md:text-sm uppercase tracking-[2px] md:tracking-[3px] flex items-center justify-center gap-3 hover:bg-red-700 transition-all shadow-xl shadow-red-900/30">
                    <i class="fa fa-play"></i> Watch Now
                </a>
                <a href="<?= $movie['download_link'] ?>" target="_blank" class="flex-1 md:flex-none text-center px-6 md:px-10 py-4 md:py-5 action-btn rounded-2xl font-black text-xs md:text-sm uppercase tracking-[2px] md:tracking-[3px] flex items-center justify-center gap-3">
                    <i class="fa fa-download"></i> Download
                </a>
            </div>
        </div>
    </div>

    <main class="px-6 md:px-16 pb-20 grid grid-cols-1 lg:grid-cols-12 gap-10 md:gap-12 mt-10">
        
        <div class="lg:col-span-8">
            <h3 class="text-gray-500 text-[10px] md:text-xs font-black uppercase tracking-[5px] mb-4">Storyline</h3>
            <p class="text-lg md:text-2xl text-gray-300 font-light leading-relaxed">
                <?= $movie['description'] ?>
            </p>
        </div>

        <div class="lg:col-span-4">
            <div class="glass-card p-6 md:p-8">
                <h4 class="text-white font-bold mb-6 border-l-2 border-red-600 pl-4 uppercase text-[10px] md:text-xs tracking-widest">Details</h4>
                <div class="space-y-6">
                    <div>
                        <p class="text-[9px] md:text-[10px] text-gray-500 uppercase font-black tracking-widest mb-1">Release Year</p>
                        <p class="text-xs md:text-sm font-bold"><?= $movie['year'] ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] md:text-[10px] text-gray-500 uppercase font-black tracking-widest mb-1">Genre</p>
                        <p class="text-xs md:text-sm font-bold"><?= $movie['category'] ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] md:text-[10px] text-gray-500 uppercase font-black tracking-widest mb-1">Rating</p>
                        <p class="text-xs md:text-sm font-bold text-yellow-500"><i class="fa fa-star"></i> <?= $movie['rating'] ?></p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="text-center py-10 md:py-20 border-t border-white/5 opacity-20">
        <p class="text-[10px] uppercase tracking-[5px] font-black">Powered by StreamX Cinema</p>
    </footer>

</body>
</html>
