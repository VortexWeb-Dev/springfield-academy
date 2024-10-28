<style>
    .navbar {
        background-color: white;
        transition: background-color 0.3s, box-shadow 0.3s;
        min-height: 70px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .navbar.scrolled {
        background-color: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .nav-link {
        text-decoration: none;
        transition: color 0.3s, font-weight 0.3s;
        font-weight: 600;
    }

    .nav-link.active {
        color: #1e293b;
        background-color: white;
    }

    .nav-link:hover {
        color: #1e293b;
    }
</style>

<script>
    // Change navbar style on scroll
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
</script>

<nav class="navbar navbar-expand-lg navbar-light sticky-top"> <!-- Sticky navbar -->

    <a class="navbar-brand d-lg-none" href="index.php">
        Academy <i class="bi bi-mortarboard-fill"></i>
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav w-100"> <!-- Full width navbar items -->
            <?php

            $nav_items = [
                ['label' => "Courses", 'url' => 'manage_courses.php', 'icon' => 'bi bi-book-fill'],
                // ['label' => "Modules", 'url' => 'manage_modules.php', 'icon' => 'bi bi-columns-gap'],
                ['label' => "Lessons", 'url' => 'manage_lessons.php', 'icon' => 'bi bi-card-checklist'],
                // ['label' => "Certificates", 'url' => 'manage_certificates.php', 'icon' => 'bi bi-mortarboard-fill'],
                // ['label' => "Quizzes", 'url' => 'manage_quizzes.php', 'icon' => 'bi bi-puzzle-fill'],
                ['label' => "Help", 'url' => 'help.php', 'icon' => 'bi bi-question-circle-fill'],
            ];

            $page_url = basename($_SERVER['REQUEST_URI']);

            foreach ($nav_items as $item) {
                $active_class = $page_url == $item['url'] ? 'active' : '';
                echo "<li class='nav-item mr-2'>
                        <a class='nav-link $active_class' href='{$item['url']}'>
                            {$item['label']}
                            <i class='{$item['icon']} ml-2'></i>
                        </a>
                      </li>";
            }
            ?>
        </ul>
    </div>
</nav>