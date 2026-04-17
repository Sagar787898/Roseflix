<?php
$series = json_decode(file_get_contents('data/series.json'), true) ?: [];

// Agar koi specific series selected hai to details dikhao, warna list
$selected_id = $_GET['id'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Series Collection</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #080808; color: white; }
        .ep-btn:hover { background: #e50914; border-color: #e50914; }
    </style>
</head>
<body>
    <nav class="p-6 border-b border-zinc-800">
        <a href="index.php" class="text-2xl font-bold">STREAM<span class="text-red-600">X</span> SERIES</a>
    </nav>

    <div class="p-6">
        <?php if(!$selected_id): ?>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                <?php foreach($series as $s): ?>
                <a href="series.php?id=<?= $s['id'] ?>" class="block group">
                    <img src="<?= $s['poster'] ?>" class="rounded-lg shadow-2xl group-hover:scale-105 transition duration-300">
                    <h3 class="mt-2 font-bold text-center"><?= $s['name'] ?></h3>
                </a>
                <?php endforeach; ?>
            </div>

        <?php else: 
            // EPISODE & SEASON SELECTION LOGIC
            $current_series = array_filter($series, fn($s) => $s['id'] == $selected_id);
            $s_data = reset($current_series);
        ?>
            <div class="max-w-6xl mx-auto">
                <div class="flex flex-col md:flex-row gap-8">
                    <img src="<?= $s_data['poster'] ?>" class="w-64 rounded-xl shadow-xl">
                    <div>
                        <h1 class="text-4xl font-bold"><?= $s_data['name'] ?></h1>
                        <p class="text-gray-400 mt-4"><?= $s_data['description'] ?></p>
                        
                        <div class="mt-8">
                            <?php foreach($s_data['seasons'] as $index => $season): ?>
                                <div class="mb-6">
                                    <h2 class="text-xl font-bold text-red-500 mb-3">Season <?= $season['season_no'] ?></h2>
                                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3">
                                        <?php foreach($season['episodes'] as $ep): ?>
                                            <a href="player.php?url=<?= base64_encode($ep['stream_link']) ?>" 
                                               class="ep-btn border border-zinc-700 p-2 text-center rounded bg-zinc-900 transition text-sm">
                                               EP <?= $ep['ep_no'] ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>