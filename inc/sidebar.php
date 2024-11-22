<style>
    #sidebar {
        height: 100vh;
        position: fixed;
        background-color: #f4f7fa;
        transition: transform 0.3s ease-in-out;
        z-index: 1100;
    }

    @media (max-width: 767.98px) {
        #sidebar {
            transform: translateX(-100%);
        }

        #sidebar.show {
            transform: translateX(0);
        }
    }

    #sidebar-toggle {
        position: fixed;
        bottom: 10px;
        right: 10px;
        z-index: 1001;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .nav-link {
        color: #343a40;
        transition: color 0.3s, background-color 0.3s;
    }

    .nav-link:hover {
        color: #94a3b8;
        text-decoration: none;
    }

    .active {
        color: white;
        background-color: #94a3b8;
    }
</style>

<!-- Floating Toggle Button -->
<button class="btn btn-primary d-md-none" id="sidebar-toggle" type="button">
    <i class="bi bi-list"></i>
</button>

<!-- sidebar.php -->
<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block sidebar px-0 py-1" style="background-color: white;">
    <div class="sidebar-sticky">
        <ul class="nav flex-column">
            <h3 class="d-flex justify-content-between justify-content-lg-center p-2">
                <a href="index.php" class="text-decoration-none text-dark" style="height: 50px;">
                    Academy <i class="bi bi-mortarboard-fill"></i>
                </a>
                <a href="index.php" class="text-decoration-none d-lg-none" id="close-sidebar">
                    <i class="bi bi-x-lg"></i>
                </a>
            </h3>
            <hr class="my-2">
            <?php
            $sidebar_items = [
                ['label' => "Dashboard", 'url' => 'index.php', 'icon' => 'bi bi-mortarboard-fill'],
                // ['label' => "Stream", 'url' => 'stream.php', 'icon' => 'bi bi-bell-fill'],
                // ['label' => "Profile", 'url' => 'profile.php', 'icon' => 'bi bi-person-fill'],
                // ['label' => "My Rating", 'url' => 'rating.php', 'icon' => 'bi bi-star-fill'],
                // ['label' => "My Tests", 'url' => 'tests.php', 'icon' => 'bi bi-clipboard-fill'],
                ['label' => "My Courses", 'url' => 'courses.php', 'icon' => 'bi bi-book-fill'],
                ['label' => "Video Lessons", 'url' => 'video_lessons.php', 'icon' => 'bi bi-play-btn-fill'],
                // ['label' => "My Groups", 'url' => 'groups.php', 'icon' => 'bi bi-people-fill'],
                // ['label' => "My Grades", 'url' => 'grades.php', 'icon' => 'bi bi-clipboard-check-fill'] ,
                // ['label' => "My Checks", 'url' => 'checks.php', 'icon' => 'bi bi-check-all'],
                // ['label' => "My Certificates", 'url' => 'certificates.php', 'icon' => 'bi bi-mortarboard-fill'],
                // ['label' => "My Competencies", 'url' => 'competencies.php', 'icon' => 'bi bi-person-badge'],
                // ['label' => "My Events", 'url' => 'events.php', 'icon' => 'bi bi-calendar-event-fill'],
                // ['label' => "My Programs", 'url' => 'programs.php', 'icon' => 'bi bi-megaphone-fill'],
                // ['label' => "Individual Development Plan", 'url' => 'development_plan.php', 'icon' => 'bi bi-signpost'],
                ['label' => "Reports", 'url' => 'reports.php', 'icon' => 'bi bi-flag-fill'],
            ];
            $page_url = basename($_SERVER['REQUEST_URI']);

            foreach ($sidebar_items as $item) {
                $active_class = $page_url == $item['url'] ? 'active pl-4' : '';
                $target = $item['target'] ?? '_self';
                echo "<li class='nav-item border-bottom'>
                        <a class='nav-link $active_class text-decoration-none' href='{$item['url']}' target=$target>
                        <i class='{$item['icon']} mr-2'></i>
                        {$item['label']}
                        </a>
                      </li>";
            }
            ?>
        </ul>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const closeSidebar = document.getElementById('close-sidebar');

        function toggleSidebar() {
            sidebar.classList.toggle('show');
        }

        sidebarToggle.addEventListener('click', toggleSidebar);

        if (closeSidebar) {
            closeSidebar.addEventListener('click', toggleSidebar);
        }

        // Close sidebar when clicking outside of it
        document.addEventListener('click', function(event) {
            const isClickInsideSidebar = sidebar.contains(event.target);
            const isClickOnToggleButton = sidebarToggle.contains(event.target);

            if (!isClickInsideSidebar && !isClickOnToggleButton && sidebar.classList.contains('show')) {
                toggleSidebar();
            }
        });
    });
</script>