<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
  <title>Nirmeek Associates CMS</title>
  <!-- Fonts and icons -->
  <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="assets/css/soft-ui-dashboard.css?v=1.1.0" rel="stylesheet" />
  <style>
    .card {
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 16px;
      margin: 16px;
      width: 100%;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .subcard {
      border: 1px solid #eee;
      border-radius: 4px;
      padding: 8px;
      margin: 8px 0;
    }

    .textfield {
      width: 100%;
      padding: 8px;
      margin: 8px 0;
      box-sizing: border-box;
    }

    .button {
      padding: 8px 16px;
      margin: 4px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }

    .button.upgrade {
      background-color: #4CAF50;
      color: white;
    }

    .button.clear {
      background-color: #f44336;
      color: white;
    }

    .image-upload {
      margin: 8px 0;
    }

    .preview-image {
      max-width: 100%;
      margin-top: 8px;
      display: none;
    }

    .new-project {
      background: #2196F3;
      margin-bottom: 20px;
    }

    .project-form {
      margin: 20px 0;
      position: relative;
    }

    .delete-project {
      background: #ff4444;
    }

    .project-form::after {
      content: "";
      display: block;
      height: 2px;
      background: #eee;
      margin: 20px 0;
    }

    /* New Styles */
    .existing-projects {
      margin: 2rem 0;
      border-top: 2px solid #eee;
      padding-top: 2rem;
      padding-left: 2rem;
    }

    .project-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1rem;
      margin: 0.5rem 0;
      background: #f8f9fa;
      border-radius: 8px;
    }

    .project-item .project-title {
      font-weight: 500;
      flex-grow: 1;
      margin-right: 1rem;
    }

    .delete-project {
      background: #ff4444;
      color: white;
      padding: 0.5rem 1rem;
      white-space: nowrap;
    }

    .category-tag {
      background: #e0e0e0;
      padding: 0.25rem 0.5rem;
      border-radius: 4px;
      font-size: 0.8rem;
      margin-left: 1rem;
    }
  </style>
</head>
<body>
  <?php include 'left.php'; ?>
  <!-- End Sidebar -->
  <!-- Navbar -->
  <?php include 'top.php'; ?>

  <div class="container">
    <h1>Projects Management</h1>
    <div class="button-group">
      <button class="button new-project" id="newProjectBtn">
        <i class="fas fa-plus"></i> Create New Project
      </button>
    </div>

    <!-- Existing Projects Section -->
 

    <!-- Project Creation Forms -->
    <div id="projectFormsContainer"></div>
  </div>

  <div class="existing-projects">
      <h2>Existing Projects</h2>
      <div id="projectsList" class="projects-list"></div>
    </div>

  <!-- Templates -->
  <template id="projectFormTemplate">
    <div class="subcard project-form">
      <div class="field-group-vertical">
        <input type="text" class="textfield project-title" placeholder="Project Title" required>
        <input type="text" class="textfield project-subtitle" placeholder="Subtitle">
        <textarea class="textfield project-details" placeholder="Details (comma separated)" required></textarea>
        <input type="text" class="textfield project-image" placeholder="Image URL" required>
        <div class="category-select">
          <label>
            <input type="radio" name="radio" value="1" required> Current
          </label>
          <label>
            <input type="radio" name="radio" value="2"> Completed
          </label>
          <label>
            <input type="radio" name="radio" value="3"> Consultation
          </label>
        </div>
      </div>
      <div class="button-group-horizontal">
        <button class="button save-project">Save Permanent</button>
        <button class="button delete-project">Cancel</button>
      </div>
    </div>
  </template>

  <template id="projectItemTemplate">
    <div class="project-item">
      <span class="project-title"></span>
      <span class="category-tag"></span>
      <button class="button delete-project">
        <i class="fas fa-trash"></i> Delete
      </button>
    </div>
  </template>

  <script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-app.js";
    import { getDatabase, ref, set, push, get, remove } from "https://www.gstatic.com/firebasejs/9.22.0/firebase-database.js";

    const firebaseConfig = {
      apiKey: "AIzaSyBgiG0yUHxZfctZarWg_U4Oq4StH2H1dGI",
      authDomain: "dynamic-745ae.firebaseapp.com",
      databaseURL: "https://dynamic-745ae-default-rtdb.firebaseio.com",
      projectId: "dynamic-745ae",
      storageBucket: "dynamic-745ae.appspot.com",
      messagingSenderId: "853829413975",
      appId: "1:853829413975:web:e0b130efeed9a4b7730712"
    };

    const app = initializeApp(firebaseConfig);
    const database = getDatabase(app);
    const projectsRef = ref(database, 'projects');

    // Load existing projects on page load
    document.addEventListener('DOMContentLoaded', loadExistingProjects);

    // New project button handler
    document.getElementById('newProjectBtn').addEventListener('click', () => {
      const template = document.getElementById('projectFormTemplate');
      const clone = template.content.cloneNode(true);
      const form = clone.querySelector('.project-form');

      form.querySelector('.save-project').addEventListener('click', saveProject);
      form.querySelector('.delete-project').addEventListener('click', () => form.remove());
      
      document.getElementById('projectFormsContainer').appendChild(clone);
    });

    async function saveProject(event) {
      const form = event.target.closest('.project-form');
      const selectedRadio = form.querySelector('input[name="radio"]:checked');

      if (!selectedRadio) {
        alert('Please select a project category');
        return;
      }

      const categoryMap = { '1': 'current', '2': 'completed', '3': 'consultation' };
      const category = categoryMap[selectedRadio.value];

      const projectData = {
        title: form.querySelector('.project-title').value,
        subtitle: form.querySelector('.project-subtitle').value,
        services: form.querySelector('.project-details').value.split(',').map(s => s.trim()),
        image: form.querySelector('.project-image').value,
        category: category,
        createdAt: Date.now()
      };

      try {
        const newProjectRef = push(projectsRef);
        await set(newProjectRef, projectData);
        form.remove();
        await loadExistingProjects();
        alert('Project saved successfully!');
      } catch (error) {
        alert('Error saving project: ' + error.message);
      }
    }

    async function loadExistingProjects() {
      const projectsList = document.getElementById('projectsList');
      projectsList.innerHTML = '';

      try {
        const snapshot = await get(projectsRef);
        if (!snapshot.exists()) {
          projectsList.innerHTML = '<p>No projects found</p>';
          return;
        }

        const projects = snapshot.val();
        Object.entries(projects).forEach(([key, project]) => {
          const template = document.getElementById('projectItemTemplate');
          const clone = template.content.cloneNode(true);
          const item = clone.querySelector('.project-item');

          item.querySelector('.project-title').textContent = project.title;
          item.querySelector('.category-tag').textContent = project.category.toUpperCase();
          
          item.querySelector('.delete-project').addEventListener('click', async () => {
            if (confirm(`Are you sure you want to delete "${project.title}"?`)) {
              try {
                await remove(ref(database, `projects/${key}`));
                item.remove();
                alert('Project deleted successfully');
              } catch (error) {
                alert('Error deleting project: ' + error.message);
              }
            }
          });

          projectsList.appendChild(clone);
        });
      } catch (error) {
        projectsList.innerHTML = `<p>Error loading projects: ${error.message}</p>`;
      }
    }
  </script>
</body>
</html>