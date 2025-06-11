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
    $issuesUrl = "$repoUrl/issues?state=open";
    $readmeUrl = "$repoUrl/readme";

    $repoData = fetchGitHubData($repoUrl, $headers);
    $commitsData = fetchGitHubData($commitsUrl, $headers);
    $contributorsData = fetchGitHubData($contributorsUrl, $headers);
    $branchesData = fetchGitHubData($branchesUrl, $headers);
    $forksData = fetchGitHubData($forksUrl, $headers);
    $mergesData = fetchGitHubData($mergesUrl, $headers);
    $clonesData = fetchGitHubData($clonesUrl, $headers);
    $languagesData = fetchGitHubData($languagesUrl, $headers);
    $issuesData = fetchGitHubData($issuesUrl, $headers);
    $readmeData = fetchGitHubData($readmeUrl, $headers);

    // Process issues data
    $openIssuesCount = 0;
    $bugIssuesCount = 0;
    $goodFirstIssueCount = 0;

    if ($issuesData && is_array($issuesData)) {
        $openIssuesCount = count($issuesData);
        
        foreach ($issuesData as $issue) {
            $labels = $issue['labels'] ?? [];
            foreach ($labels as $label) {
                $labelName = strtolower($label['name']);
                if (strpos($labelName, 'bug') !== false) {
                    $bugIssuesCount++;
                }
                if (strpos($labelName, 'good first issue') !== false || strpos($labelName, 'good-first-issue') !== false) {
                    $goodFirstIssueCount++;
                }
            }
        }
    }

    $latestCommit = isset($commitsData[0]) ? $commitsData[0]['commit']['author'] : null;

    // Enhanced contributors with profile pictures
    $contributors = [];
    if ($contributorsData && is_array($contributorsData)) {
        foreach ($contributorsData as $contributor) {
            $contributors[] = [
                'login' => $contributor['login'],
                'avatar_url' => $contributor['avatar_url'],
                'html_url' => $contributor['html_url'],
                'contributions' => $contributor['contributions']
            ];
        }
    }

    $branches = array_map(fn($branch) => "<a href='{$repoData['html_url']}/tree/{$branch['name']}' target='_blank'>{$branch['name']}</a>", $branchesData ?? []);
    $forksCount = count($forksData ?? []);
    $mergesCount = count($mergesData ?? []);
    $clonesCount = $clonesData['count'] ?? 'N/A';
    $languages = implode(', ', array_keys($languagesData)) ?: 'N/A';

    // Process commit activity by contributor
    $commitsByContributor = [];
    if ($commitsData && is_array($commitsData)) {
        foreach ($commitsData as $commit) {
            $author = $commit['commit']['author']['name'] ?? 'Unknown';
            if (!isset($commitsByContributor[$author])) {
                $commitsByContributor[$author] = [];
            }
            $commitsByContributor[$author][] = [
                'date' => $commit['commit']['author']['date'],
                'message' => $commit['commit']['message'],
                'sha' => $commit['sha']
            ];
        }
    }
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
                        <li><strong>Contributors:</strong> 
                            <div id="contributors-list">
                                <?php if (!empty($contributors)): ?>
                                    <?php foreach ($contributors as $contributor): ?>
                                        <div class="contributor-item">
                                            <img src="<?= $contributor['avatar_url'] ?>" alt="<?= $contributor['login'] ?>" class="contributor-avatar">
                                            <a href="<?= $contributor['html_url'] ?>" target="_blank" class="contributor-link">
                                                <?= $contributor['login'] ?>
                                            </a>
                                            <span class="contribution-count">(<?= $contributor['contributions'] ?> contributions)</span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span>No contributors found</span>
                                <?php endif; ?>
                            </div>
                        </li>
                        <li><strong>Last Commit:</strong> 
                            <span id="commits"> <?= $latestCommit['name'] ?? 'N/A' ?> on <?= $latestCommit['date'] ?? 'N/A' ?> </span>
                            <button class="interactive-btn" onclick="toggleCommitActivity()">
                                <i class="fas fa-chart-line"></i> View Commit Activity
                            </button>
                        </li>
                        <li><strong>Merges:</strong> <span id="merges"> <?= $mergesCount ?? 'N/A' ?> </span></li>
                        <li><strong>Clones:</strong> <span id="clones"> <?= $clonesCount ?> </span></li>
                        <li><strong>Languages Used:</strong> 
                            <span id="languages"> <?= implode(', ', array_keys($languages)) ?: 'N/A' ?> </span>
                            <?php if (!empty($languages)): ?>
                                <button class="interactive-btn" onclick="toggleLanguageChart()">
                                    <i class="fas fa-chart-pie"></i> View Language Distribution
                                </button>
                            <?php endif; ?>
                        </li>
                        <li><strong>Issues:</strong>
                            <div class="issues-container">
                                <div class="issue-stat">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>Open Issues: <?= $openIssuesCount ?></span>
                                </div>
                                <div class="issue-stat bug">
                                    <i class="fas fa-bug"></i>
                                    <span>Bugs: <?= $bugIssuesCount ?></span>
                                </div>
                                <div class="issue-stat good-first">
                                    <i class="fas fa-seedling"></i>
                                    <span>Good First Issues: <?= $goodFirstIssueCount ?></span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- README Section -->
        <?php if (!empty($readmeContent)): ?>
        <section id="readme-section">
            <h3><i class="fas fa-file-alt"></i> README</h3>
            <div class="readme-content">
                <?= $readmeContent ?>
            </div>
        </section>
        <?php endif; ?>

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
