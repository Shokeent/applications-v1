# GitHub Repository Explorer

A **GitHub Repository Explorer** that dynamically fetches and displays public repositories from a specified GitHub user. Users can view repository details, languages used, and navigate through repositories using a **pagination system**.

## 🛠 Features

- Fetches **all public repositories** of a user.  
- Displays **repository details** (name, description, languages, and GitHub link).  
- **Pagination system** with **Next/Previous** buttons and page count (`1/20`).  
- Fully **responsive design** for mobile, tablet, and desktop.  
- Clickable **BrickMMO Logo** redirects to the homepage.  
- **Secure API calls** to public GitHub repositories.  

---

## 📌 Tech Stack

- **Frontend**: HTML, CSS, JavaScript  
- **Backend**: PHP  
- **API**: GitHub REST API v3  
- **Styling**: Font Awesome Icons  

---

## 🚀 Installation & Setup

### 1️⃣ Clone the Repository
```bash
git clone https://github.com/awsactivators/brickmmo-application-api.git
cd brickmmo-application-api
```


### 2️⃣ Start the Local Development Server
Run PHP's built-in server:
```bash
php -S localhost:8000
```
Then, open **`http://localhost:8000/`** in your browser.

---

## 🎯 Usage

### **🔥 Home Page**
- **Loads all public repositories** of the specified GitHub user.
- **Displays repository details** (name, description, languages used).
- **Pagination system** lets you browse repositories **9 per page**.
- Clicking **"GitHub"** opens the repo's **GitHub link**.
- Clicking **"View Details"** navigates to the **repo details page**.

### **📜 Repository Details Page**
- Fetches **detailed information** about a repository.
- Displays **latest commit**, **Merges**, **contributors**, **languages**, **forks**, and **branches**.
- **GitHub link button** redirects users to the repo.

---

## 🛠 API Calls Used

- **Fetch all public repositories**
  ```bash
  GET https://api.github.com/users/{username}/repos?per_page=100&page={page}
  ```
- **Fetch repository details**
  ```bash
  GET https://api.github.com/repos/{owner}/{repo}
  ```
- **Fetch repository languages**
  ```bash
  GET https://api.github.com/repos/{owner}/{repo}/languages
  ```
- **Fetch repository contributors**
  ```bash
  GET https://api.github.com/repos/{owner}/{repo}/contributors
  ```
- **Fetch repository Forks**
  ```bash
  GET https://api.github.com/repos/{owner}/{repo}/forks
  ```
- **Fetch repository Merges**
  ```bash
  GET https://api.github.com/repos/{owner}/{repo}/pulls?state=closed
  ```
- **Fetch repository Clones**
  ```bash
  GET https://api.github.com/repos/{owner}/{repo}/traffic/clones
  ```
- **Fetch repository Branches**
  ```bash
  GET https://api.github.com/repos/{owner}/{repo}/branches
  ```
- **Fetch repository Commits**
  ```bash
  GET https://api.github.com/repos/{owner}/{repo}/commits
  ```

---

## 🎨 UI & Responsiveness

- Fully **responsive** for **mobile, tablet, and desktop**.
- Uses **CSS Grid & Flexbox** for layout.
- Font Awesome icons for **GitHub, YouTube, Twitter, Instagram, and TikTok**.

---


## 🎯 Next Steps

- Add **search functionality** to find specific repos.
- Implement **star/unstar repo** functionality.
- Implement contibutors badge

---

## 🐝 License

This project is **open-source** under the **MIT License**. You can modify and distribute it freely. 🚀  

---

### 🔗 Connect with Me  
[![GitHub](https://img.shields.io/badge/GitHub-Profile-black?style=flat&logo=github)](https://github.com/awsactivators)  
[![LinkedIn](https://img.shields.io/badge/LinkedIn-Connect-blue?style=flat&logo=linkedin)](https://linkedin.com/in/vieve-awa)  
[![Medium](https://img.shields.io/badge/Medium-Follow-blue?style=flat&logo=medium)](https://medium.com/@awavieve)  

---

🚀 **Happy Coding!** 🎯
