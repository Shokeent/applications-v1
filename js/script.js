// targetting the hamburger links and hamburger icon
// will either add or remove the open class to the menu and icon

function toggleMenu() {
  const menu = document.querySelector(".hamburger-links");
  const icon = document.querySelector(".hamburger-icon");
  
  menu.classList.toggle("active");
  icon.classList.toggle("active");
}

// Dynamic GitHub Repository Fetching
const repoContainer = document.getElementById("repo-container"); 
const paginationContainer = document.getElementById("pagination");
const githubUsername = "brickmmo"; 
const perPage = 9; 
let currentPage = 1;
let allRepos = [];

// Fetch All Repositories with Pagination Support
async function fetchRepos(page = 1) {
  try {
    const response = await fetch(`https://api.github.com/users/${githubUsername}/repos?per_page=200&page=${page}`);
    const repos = await response.json();

    if (!Array.isArray(repos)) {
      console.error("GitHub API response is not an array:", repos);
      repoContainer.innerHTML = "<p>Failed to load repositories.</p>";
      return;
    }

    allRepos = allRepos.concat(repos);

    // If 100 repos were fetched, there might be more pages
    if (repos.length === 100) {
      // Recursively fetch next pages
      await fetchRepos(page + 1); 
    } else {
      renderRepos();
    }
  } catch (error) {
    console.error("Error fetching GitHub repositories:", error);
    repoContainer.innerHTML = "<p>Failed to load repositories.</p>";
  }
}

// Render Repositories with Pagination
async function renderRepos() {
  repoContainer.innerHTML = ""; 
  const start = (currentPage - 1) * perPage;
  const end = start + perPage;
  const reposToShow = allRepos.slice(start, end);

  for (const repo of reposToShow) {
    const languages = await fetchLanguages(repo.languages_url);
    const repoCard = document.createElement("div");
    repoCard.classList.add("app-card");

    repoCard.innerHTML = `
      <h3 class="card-title">${repo.name}</h3>
      <p class="app-description">${repo.description || "No description available"}</p>
      <p><strong>Languages:</strong> ${languages || "N/A"}</p>
      <div class="card-buttons-container">
        <button class="button-app-info" onclick="window.open('${repo.html_url}', '_blank')">GitHub</button>
        <button class="button-app-github" onclick="window.location.href='repo_details.php?repo=${repo.name}'">View Details</button>
      </div>
    `;

    repoContainer.appendChild(repoCard);
  }

  renderPagination();
}

// Fetch Languages for Each Repo
async function fetchLanguages(url) {
  try {
    const response = await fetch(url);
    const data = await response.json();
    return Object.keys(data).join(", ");
  } catch (error) {
    console.error("Error fetching languages:", error);
    return "N/A";
  }
}

// Pagination Controls with Next/Prev and Page Count
function renderPagination() {
  paginationContainer.innerHTML = ""; 
  const totalPages = Math.ceil(allRepos.length / perPage);

  if (totalPages > 1) {
    // Previous Button
    if (currentPage > 1) {
      const prevButton = document.createElement("button");
      prevButton.innerText = "Previous";
      prevButton.classList.add("pagination-btn");
      prevButton.onclick = () => {
        currentPage--;
        renderRepos();
      };
      paginationContainer.appendChild(prevButton);
    }

    // Page Indicator "1/20"
    const pageIndicator = document.createElement("span");
    pageIndicator.classList.add("page-indicator");
    pageIndicator.innerText = `${currentPage} / ${totalPages}`;
    paginationContainer.appendChild(pageIndicator);

    // Next Button
    if (currentPage < totalPages) {
      const nextButton = document.createElement("button");
      nextButton.innerText = "Next";
      nextButton.classList.add("pagination-btn");
      nextButton.onclick = () => {
        currentPage++;
        renderRepos();
      };
      paginationContainer.appendChild(nextButton);
    }
  }
}

// Load repositories when the page loads
document.addEventListener("DOMContentLoaded", () => fetchRepos());
