<?php
if (isset($_GET['repo'])) {
    $repoName = $_GET['repo'];
    $owner = "BrickMMO"; 

    $headers = [
        "User-Agent: BrickMMO-WebApp"
    ];

    function fetchGitHubData($url, $headers) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

    $repoUrl = "https://api.github.com/repos/$owner/$repoName";
    $commitsUrl = "$repoUrl/commits";
    $contributorsUrl = "$repoUrl/contributors";
    $branchesUrl = "$repoUrl/branches";
    $forksUrl = "$repoUrl/forks";
    $mergesUrl = "$repoUrl/pulls?state=closed";
    $clonesUrl = "$repoUrl/traffic/clones";
    $languagesUrl = "$repoUrl/languages";

    $repoData = fetchGitHubData($repoUrl, $headers);
    $commitsData = fetchGitHubData($commitsUrl, $headers);
    $contributorsData = fetchGitHubData($contributorsUrl, $headers);
    $branchesData = fetchGitHubData($branchesUrl, $headers);
    $forksData = fetchGitHubData($forksUrl, $headers);
    $mergesData = fetchGitHubData($mergesUrl, $headers);
    $clonesData = fetchGitHubData($clonesUrl, $headers);
    $languagesData = fetchGitHubData($languagesUrl, $headers);

    $latestCommit = isset($commitsData[0]) ? $commitsData[0]['commit']['author'] : null;
    $contributors = array_map(fn($contributor) => "<a href='https://github.com/{$contributor['login']}' target='_blank'>{$contributor['login']}</a>", $contributorsData ?? []);
    $branches = array_map(fn($branch) => "<a href='{$repoData['html_url']}/tree/{$branch['name']}' target='_blank'>{$branch['name']}</a>", $branchesData ?? []);
    $forksCount = count($forksData ?? []);
    $mergesCount = count($mergesData ?? []);
    $clonesCount = $clonesData['count'] ?? 'N/A';
    $languages = implode(', ', array_keys($languagesData)) ?: 'N/A';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repository Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="./css/detail.css">
</head>
<body>
    <header>
        <div class="logo">
            <a href="index.html">
                <img src="./assets/BrickMMO_Logo_Coloured.png" alt="brickmmo logo" width="80px">
            </a>
        </div>
        <nav>
            <a href="index.html" class="return-btn">&larr; Return</a>
        </nav>
    </header>
    <main>
        <section id="repo-card">
            <div class="repo-image">
                <img src="<?= $repoData['owner']['avatar_url'] ?? './assets/placeholder.png' ?>" alt="Repository Image">
            </div>
            <div class="repo-info">
                <div id="repo-brief">
                    <h2 id="repo-title"> <?= htmlspecialchars($repoData['name'] ?? 'N/A') ?> </h2>
                    <p id="repo-description"> <?= htmlspecialchars($repoData['description'] ?? 'No description available') ?> </p>
                    <a id="repo-link" href="<?= $repoData['html_url'] ?? '#' ?>" target="_blank" class="github-btn">GitHub Link</a>
                </div>
                <div id="repo-details">
                    <h3>Repository Details</h3>
                    <ul>
                        <li><strong>Forks:</strong> <span id="forks"> <?= $forksCount ?? 'N/A' ?> </span></li>
                        <li><strong>Branches:</strong> <span id="branches"> <?= implode(', ', $branches) ?: 'N/A' ?> </span></li>
                        <li><strong>Contributors:</strong> <span id="contributors"> <?= implode(', ', $contributors) ?: 'N/A' ?> </span></li>
                        <li><strong>Last Commit:</strong> <span id="commits"> <?= $latestCommit['name'] ?? 'N/A' ?> on <?= $latestCommit['date'] ?? 'N/A' ?> </span></li>
                        <li><strong>Merges:</strong> <span id="merges"> <?= $mergesCount ?? 'N/A' ?> </span></li>
                        <li><strong>Clones:</strong> <span id="clones"> <?= $clonesCount ?> </span></li>
                        <li><strong>Languages Used:</strong> <span id="languages"> <?= $languages ?> </span></li>
                    </ul>
                </div>
            </div>
        </section>
    </main>
    <footer>
        <div class="social-icons">
            <a href="https://www.instagram.com/brickmmo/" target="_blank"><i class="fab fa-instagram"></i></a>
            <a href="https://www.youtube.com/channel/UCJJPeP10HxC1qwX_paoHepQ" target="_blank"><i class="fab fa-youtube"></i></a>
            <a href="https://x.com/brickmmo" target="_blank"><i class="fab fa-x"></i></a>
            <a href="https://github.com/BrickMMO" target="_blank"><i class="fab fa-github"></i></a>
            <a href="https://www.tiktok.com/@brickmmo" target="_blank"><i class="fab fa-tiktok"></i></a>
        </div>
        <p>&copy; BrickMMO, 2025. All rights reserved.</p>
        <p>LEGO, the LEGO logo and the Minifigure are trademarks of the LEGO Group.</p>
    </footer>
</body>
</html>
