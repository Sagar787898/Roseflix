<?php
session_start();
if(!isset($_SESSION['admin'])) { header('Location: login.php'); exit; }

$movie_file = 'data/movies.json';
$series_file = 'data/series.json';

function getJSON($f) { return json_decode(file_get_contents($f), true) ?: []; }
function saveJSON($f, $d) { file_put_contents($f, json_encode(array_values($d), JSON_PRETTY_PRINT)); }

$msg = ""; $msg_type = "";

// Actions Handling
if(isset($_GET['delete'])){
    $file = ($_GET['type'] == 'series') ? $series_file : $movie_file;
    $data = getJSON($file);
    $data = array_filter($data, fn($item) => $item['id'] != $_GET['delete']);
    saveJSON($file, $data);
    header("Location: admin.php?status=deleted&tab=" . $_GET['type']); exit;
}

if(isset($_POST['save_movie'])){
    $data = getJSON($movie_file);
    $entry = ["id" => $_POST['id'] ?: time(), "name" => $_POST['name'], "poster" => $_POST['poster'], "category" => $_POST['category'], "description" => $_POST['description'], "stream_link" => $_POST['stream_link'], "download_link" => $_POST['download_link'], "type" => "movie", "year" => $_POST['year'], "rating" => $_POST['rating']];
    if(!empty($_POST['id'])){ foreach($data as $k => $v) if($v['id'] == $_POST['id']) $data[$k] = $entry; }
    else { array_unshift($data, $entry); }
    saveJSON($movie_file, $data);
    header("Location: admin.php?status=success&tab=movie"); exit;
}

if(isset($_POST['save_series'])){
    $data = getJSON($series_file);
    $entry = ["id" => $_POST['id'] ?: "s".time(), "name" => $_POST['name'], "poster" => $_POST['poster'], "description" => $_POST['description'], "type" => "series", "seasons" => $_POST['seasons']];
    if(!empty($_POST['id'])){ foreach($data as $k => $v) if($v['id'] == $_POST['id']) $data[$k] = $entry; }
    else { array_unshift($data, $entry); }
    saveJSON($series_file, $data);
    header("Location: admin.php?status=success&tab=series"); exit;
}

if(isset($_GET['status'])){
    $msg = "Action Successful!";
    $msg_type = "bg-green-600";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StreamX | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');
        body { background: #0a0a0a; color: #eee; font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass { background: #141414; border: 1px solid #222; border-radius: 16px; }
        .input-dark { background: #1a1a1a !important; border: 1px solid #333 !important; color: #fff; border-radius: 10px; padding: 10px; width: 100%; font-size: 14px; }
        .input-dark:focus { border-color: #e50914 !important; outline: none; }
        .tab-btn { color: #888; border-bottom: 2px solid transparent; padding: 10px 20px; font-weight: 600; font-size: 13px; text-transform: uppercase; cursor: pointer; }
        .tab-btn.active { color: #e50914; border-bottom-color: #e50914; }
        .list-card { background: #111; border: 1px solid #222; border-radius: 12px; transition: 0.2s; }
        .list-card:hover { border-color: #444; background: #161616; }
        .hidden { display: none; }
    </style>
</head>
<body class="p-4 md:p-8">

    <?php if($msg): ?>
        <div id="notif" class="fixed top-5 right-5 z-50 <?= $msg_type ?> text-white px-6 py-3 rounded-lg shadow-xl text-sm font-bold">
            <i class="fa fa-check-circle mr-2"></i> <?= $msg ?>
        </div>
        <script>setTimeout(() => document.getElementById('notif').remove(), 3000);</script>
    <?php endif; ?>

    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-extrabold tracking-tighter uppercase">Admin<span class="text-red-600">Panel</span></h1>
            <a href="logout.php" class="bg-red-600/10 text-red-500 px-4 py-2 rounded-lg text-xs font-bold uppercase border border-red-600/20">Logout</a>
        </div>

        <div class="flex gap-4 border-b border-white/5 mb-8">
            <button onclick="switchTab('movie')" id="t-movie" class="tab-btn active">Movies</button>
            <button onclick="switchTab('series')" id="t-series" class="tab-btn">Series</button>
        </div>

        <div id="sec-movie" class="tab-pane">
            <form method="POST" class="glass p-5 mb-10">
                <h3 class="text-sm font-bold text-red-600 mb-4 uppercase">Movie Editor</h3>
                <input type="hidden" name="id" id="m_id">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="text" name="name" id="m_name" placeholder="Title" class="input-dark" required>
                    <input type="text" name="poster" id="m_poster" placeholder="Poster URL" class="input-dark" required>
                    <input type="text" name="category" id="m_cat" placeholder="Genre" class="input-dark">
                    <input type="text" name="year" id="m_year" placeholder="Year" class="input-dark">
                    <input type="text" name="rating" id="m_rating" placeholder="Rating" class="input-dark">
                    <input type="text" name="stream_link" id="m_stream" placeholder="Stream URL" class="input-dark">
                    <input type="text" name="download_link" id="m_download" placeholder="Download URL" class="input-dark">
                    <textarea name="description" id="m_desc" placeholder="Storyline" class="input-dark md:col-span-2" rows="1"></textarea>
                    <div class="flex gap-2">
                        <button name="save_movie" class="flex-1 bg-red-600 text-white font-bold rounded-lg text-xs h-[42px] uppercase">Save</button>
                        <button type="button" onclick="location.reload()" class="w-12 border border-white/10 rounded-lg"><i class="fa fa-sync-alt text-xs"></i></button>
                    </div>
                </div>
            </form>

            <div class="space-y-4">
                <div class="flex items-center glass px-4 py-2 w-full md:w-64">
                    <i class="fa fa-search text-gray-500 text-xs mr-3"></i>
                    <input type="text" onkeyup="filterCards('movieList', this.value)" placeholder="Search movies..." class="bg-transparent border-none outline-none text-sm w-full">
                </div>
                <div id="movieList" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <?php foreach(getJSON($movie_file) as $m): ?>
                    <div class="list-card p-3 flex items-center gap-4 content-card" data-name="<?= strtolower($m['name']) ?>">
                        <img src="<?= $m['poster'] ?>" class="w-12 h-16 rounded object-cover shadow-lg">
                        <div class="flex-1 overflow-hidden"><h4 class="font-bold text-sm truncate"><?= $m['name'] ?></h4><p class="text-[10px] text-gray-500 uppercase"><?= $m['year'] ?> • <?= $m['category'] ?></p></div>
                        <div class="flex gap-2">
                            <button onclick='editMovie(<?= json_encode($m) ?>)' class="w-8 h-8 rounded-full bg-blue-500/10 text-blue-500 flex items-center justify-center text-xs"><i class="fa fa-edit"></i></button>
                            <a href="?delete=<?= $m['id'] ?>&type=movie" onclick="return confirm('Delete?')" class="w-8 h-8 rounded-full bg-red-500/10 text-red-500 flex items-center justify-center text-xs"><i class="fa fa-trash"></i></a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div id="sec-series" class="tab-pane hidden">
            <form method="POST" class="glass p-5 mb-10">
                <h3 class="text-sm font-bold text-blue-500 mb-4 uppercase">Series Editor</h3>
                <input type="hidden" name="id" id="s_id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="name" id="s_name" placeholder="Series Title" class="input-dark" required>
                    <input type="text" name="poster" id="s_poster" placeholder="Poster URL" class="input-dark" required>
                    <textarea name="description" id="s_desc" placeholder="Storyline" class="input-dark md:col-span-2" rows="1"></textarea>
                </div>
                <div id="season-list" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4"></div>
                <div class="flex gap-2 mt-6">
                    <button type="button" onclick="addSeason()" class="flex-1 border border-blue-500/30 text-blue-500 text-[10px] font-bold uppercase rounded-lg h-[42px]">+ Add Season</button>
                    <button name="save_series" class="flex-1 bg-blue-600 text-white text-[10px] font-bold uppercase rounded-lg h-[42px]">Save Series</button>
                </div>
            </form>

            <div class="space-y-4">
                <div class="flex items-center glass px-4 py-2 w-full md:w-64">
                    <i class="fa fa-search text-gray-500 text-xs mr-3"></i>
                    <input type="text" onkeyup="filterCards('seriesList', this.value)" placeholder="Search series..." class="bg-transparent border-none outline-none text-sm w-full">
                </div>
                <div id="seriesList" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <?php foreach(getJSON($series_file) as $s): ?>
                    <div class="list-card p-3 flex items-center gap-4 content-card" data-name="<?= strtolower($s['name']) ?>">
                        <img src="<?= $s['poster'] ?>" class="w-12 h-16 rounded object-cover shadow-lg">
                        <div class="flex-1 overflow-hidden"><h4 class="font-bold text-sm truncate"><?= $s['name'] ?></h4><p class="text-[10px] text-gray-500 uppercase">Web Series</p></div>
                        <div class="flex gap-2">
                            <button onclick='editSeries(<?= json_encode($s) ?>)' class="w-8 h-8 rounded-full bg-blue-500/10 text-blue-500 flex items-center justify-center text-xs"><i class="fa fa-edit"></i></button>
                            <a href="?delete=<?= $s['id'] ?>&type=series" onclick="return confirm('Delete?')" class="w-8 h-8 rounded-full bg-red-500/10 text-red-500 flex items-center justify-center text-xs"><i class="fa fa-trash"></i></a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        let sCount = 0;
        function switchTab(t) {
            document.querySelectorAll('.tab-pane').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
            document.getElementById('sec-' + t).classList.remove('hidden');
            document.getElementById('t-' + t).classList.add('active');
        }

        function filterCards(parentId, value) {
            const term = value.toLowerCase();
            const cards = document.querySelectorAll(`#${parentId} .content-card`);
            cards.forEach(card => {
                card.style.display = card.dataset.name.includes(term) ? 'flex' : 'none';
            });
        }

        function addSeason(data = null) {
            const container = document.getElementById('season-list');
            const sIdx = sCount++;
            const sNum = data ? data.season_no : (sIdx + 1);
            let epHTML = '';
            if(data && data.episodes) {
                data.episodes.forEach((ep, eIdx) => { epHTML += createEpHTML(sIdx, eIdx, ep); });
            }
            const html = `
            <div class="bg-white/5 p-4 rounded-xl border border-white/5" id="s_box_${sIdx}">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Season ${sNum}</span>
                    <input type="hidden" name="seasons[${sIdx}][season_no]" value="${sNum}">
                    <button type="button" onclick="addEpisode(${sIdx})" class="text-[9px] bg-blue-600/20 text-blue-400 px-3 py-1 rounded-md font-bold uppercase tracking-widest">+ EP</button>
                </div>
                <div id="ep_container_${sIdx}" class="space-y-3">${epHTML}</div>
            </div>`;
            container.insertAdjacentHTML('beforeend', html);
        }

        function createEpHTML(sIdx, eIdx, ep = null) {
            return `
            <div class="bg-black/40 p-3 rounded-lg border border-white/5 relative">
                <div class="grid grid-cols-12 gap-2">
                    <input type="text" name="seasons[${sIdx}][episodes][${eIdx}][ep_no]" value="${ep ? ep.ep_no : (eIdx+1)}" class="col-span-3 input-dark !p-1 text-center text-xs" placeholder="EP">
                    <input type="text" name="seasons[${sIdx}][episodes][${eIdx}][stream_link]" value="${ep ? ep.stream_link : ''}" placeholder="Stream URL" class="col-span-9 input-dark !p-1 text-[11px]">
                    <input type="text" name="seasons[${sIdx}][episodes][${eIdx}][download_link]" value="${ep ? ep.download_link : ''}" placeholder="Download URL" class="col-span-10 input-dark !p-1 text-[11px]">
                    <button type="button" onclick="this.parentElement.parentElement.remove()" class="col-span-2 text-red-500 flex items-center justify-center"><i class="fa fa-times text-xs"></i></button>
                </div>
            </div>`;
        }

        function addEpisode(sIdx) {
            const container = document.getElementById(`ep_container_${sIdx}`);
            container.insertAdjacentHTML('beforeend', createEpHTML(sIdx, container.children.length));
        }

        function editMovie(m) {
            switchTab('movie');
            document.getElementById('m_id').value = m.id;
            document.getElementById('m_name').value = m.name;
            document.getElementById('m_poster').value = m.poster;
            document.getElementById('m_cat').value = m.category;
            document.getElementById('m_year').value = m.year;
            document.getElementById('m_stream').value = m.stream_link;
            document.getElementById('m_download').value = m.download_link;
            document.getElementById('m_rating').value = m.rating || '';
            document.getElementById('m_desc').value = m.description || '';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function editSeries(s) {
            switchTab('series');
            document.getElementById('s_id').value = s.id;
            document.getElementById('s_name').value = s.name;
            document.getElementById('s_poster').value = s.poster;
            document.getElementById('s_desc').value = s.description || '';
            document.getElementById('season-list').innerHTML = '';
            sCount = 0;
            if(s.seasons) s.seasons.forEach(sea => addSeason(sea));
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        const params = new URLSearchParams(window.location.search);
        if(params.get('tab')) switchTab(params.get('tab'));
    </script>
</body>
</html>
