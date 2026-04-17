<?php
$id = $_GET['id'] ?? '';
$series_list = json_decode(file_get_contents('data/series.json'), true) ?: [];

// Find the specific series
$series = null;
foreach($series_list as $s) {
    if($s['id'] == $id) { $series = $s; break; }
}

if(!$series) { die("<h1 style='color:white;text-align:center;margin-top:50px;'>Series Not Found!</h1>"); }

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
    <title><?= $series['name'] ?> | Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;800&display=swap');
        body { background: #050505; color: #fff; font-family: 'Plus Jakarta Sans', sans-serif; }
        .hero-bg { 
            background: linear-gradient(to bottom, rgba(5,5,5,0.2), #050505), url('<?= $series['poster'] ?>');
            background-size: cover; background-position: center;
        }
        .episode-btn { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); transition: 0.4s; }
        .episode-btn:hover { background: #e50914; border-color: #e50914; transform: translateY(-5px); box-shadow: 0 10px 20px rgba(229, 9, 20, 0.2); }
        .tab-season.active { background: #e50914; color: white; border-color: #e50914; box-shadow: 0 0 15px rgba(229, 9, 20, 0.4); }
        .hidden { display: none; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body>

    <div class="hero-bg h-[60vh] md:h-[65vh] flex flex-col justify-end p-6 md:px-20 pb-12 md:pb-16 relative">
        <a href="index.php" class="absolute top-10 left-6 md:left-20 w-10 h-10 md:w-12 md:h-12 bg-black/40 rounded-full flex items-center justify-center backdrop-blur-xl border border-white/10 hover:bg-red-600 transition z-50">
            <i class="fa fa-arrow-left"></i>
        </a>
        <div class="max-w-4xl relative z-10 animate-fade-in">
            <div class="flex items-center gap-3 mb-4">
                <span class="bg-red-600 px-3 py-1 rounded text-[9px] md:text-[10px] font-black tracking-[2px]">WEB SERIES</span>
                <span class="text-gray-400 text-xs md:text-sm font-bold uppercase tracking-wider">Category: <?= $series['category'] ?? 'General' ?></span>
            </div>
            <h1 class="text-4xl md:text-8xl font-black mb-6 md:mb-8 uppercase italic tracking-tighter leading-[1.1] md:leading-[1]">
                <?= $series['name'] ?>
            </h1>
            <p class="text-gray-300 text-sm md:text-xl max-w-2xl leading-relaxed font-light opacity-80 line-clamp-3 md:line-clamp-none">
                <?= $series['description'] ?>
            </p>
        </div>
    </div>

    <div class="px-6 md:px-20 pb-20 -mt-6 md:-mt-8 relative z-20">
        
        <div class="flex gap-3 md:gap-4 mb-8 md:mb-12 overflow-x-auto pb-4 no-scrollbar">
            <?php if(!empty($series['seasons'])): ?>
                <?php foreach($series['seasons'] as $index => $season): ?>
                    <button onclick="showSeason(<?= $index ?>)" 
                            id="btn-s-<?= $index ?>"
                            class="tab-season px-8 md:px-10 py-3 md:py-4 rounded-2xl border border-white/10 font-black uppercase text-[10px] md:text-xs tracking-widest transition-all duration-300 whitespace-nowrap <?= $index === 0 ? 'active' : '' ?>">
                        Season <?= $season['season_no'] ?>
                    </button>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if(!empty($series['seasons'])): ?>
            <?php foreach($series['seasons'] as $index => $season): ?>
                <div id="season-content-<?= $index ?>" 
                     class="season-panel <?= $index === 0 ? '' : 'hidden' ?> grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6 animate-fade-in">
                    
                    <?php if(!empty($season['episodes'])): ?>
                        <?php foreach($season['episodes'] as $ep): ?>
                            <div class="episode-btn p-5 md:p-6 rounded-[24px] md:rounded-[28px] flex items-center justify-between group">
                                <div class="flex items-center gap-4 md:gap-5">
                                    <div class="w-10 h-10 md:w-12 md:h-12 rounded-2xl bg-white/5 flex items-center justify-center font-black text-base md:text-lg border border-white/5 group-hover:bg-white/20 transition">
                                        <?= $ep['ep_no'] ?>
                                    </div>
                                    <div>
                                        <h4 class="font-extrabold text-xs md:text-sm tracking-wide group-hover:text-white transition uppercase">EPISODE <?= $ep['ep_no'] ?></h4>
                                        <p class="text-[8px] md:text-[9px] text-gray-500 uppercase font-black tracking-widest mt-1">HD Streaming</p>
                                    </div>
                                </div>
                                <div class="flex gap-2 md:gap-3">
                                    <a href="player.php?url=<?= b64($ep['stream_link']) ?>" 
                                       class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-white/5 text-white flex items-center justify-center hover:bg-white hover:text-black transition shadow-xl">
                                        <i class="fa fa-play text-[10px]"></i>
                                    </a>
                                    <?php if(!empty($ep['download_link'])): ?>
                                    <a href="<?= $ep['download_link'] ?>" target="_blank" 
                                       class="w-9 h-9 md:w-10 md:h-10 rounded-full bg-white/5 text-gray-400 flex items-center justify-center hover:bg-blue-600 hover:text-white transition">
                                        <i class="fa fa-download text-[10px]"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-gray-500 col-span-full italic">No episodes added for this season yet.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="py-20 text-center glass rounded-3xl">
                <p class="text-gray-500 font-bold uppercase tracking-widest">No seasons available.</p>
            </div>
        <?php endif; ?>

    </div>

    <script>
        function showSeason(index) {
            const panels = document.querySelectorAll('.season-panel');
            panels.forEach(p => p.classList.add('hidden'));

            const buttons = document.querySelectorAll('.tab-season');
            buttons.forEach(b => b.classList.remove('active'));

            const targetPanel = document.getElementById('season-content-' + index);
            const targetBtn = document.getElementById('btn-s-' + index);

            if(targetPanel) {
                targetPanel.classList.remove('hidden');
                targetPanel.style.opacity = 0;
                setTimeout(() => { targetPanel.style.opacity = 1; targetPanel.style.transition = '0.4s'; }, 10);
            }
            if(targetBtn) targetBtn.classList.add('active');
        }
    </script>
</body>
</html>
