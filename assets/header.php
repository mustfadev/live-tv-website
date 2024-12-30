<header>
    <nav class="navbar">
        <div class="nav-section nav-left">
            <!-- Button for opening the sidebar menu -->
            <button class="nav-button menu-button">
                <i class="uil uil-bars"></i> <!-- Icon representing the menu -->
            </button>
            <!-- Logo linking to the homepage -->
            <a href="index.php" class="nav-logo">
                <img src="assets/images/logo.png" alt="Logo" width="35" class="logo"> <!-- Logo image -->
                <h2 class="logo-text"><?php echo htmlspecialchars($row["name"]); ?></h2> <!-- Output site name safely -->
            </a>
        </div>

        <div class="nav-section nav-center">
            <!-- Search form for users to search channels -->
            <form action="search_results.php" method="GET" class="search-form">
                <input type="search" name="keyword" placeholder="Search channels" class="search-input" required aria-label="Search"> <!-- Search input field -->
                <button class="nav-button search-button" type="submit"> <!-- Search button -->
                    <i class="uil uil-search"></i> <!-- Icon for the search button -->
                </button>
            </form>
        </div>

        <div class="nav-section nav-right">
            <!-- Additional button for search functionality (optional) -->
            <button class="nav-button search-button"  id="searchDropdown">
                <i class="uil uil-search"></i> <!-- Search icon -->
            </button>
            <!-- Button to toggle dark/light theme -->
            <button class="nav-button theme-button">
                <i class="uil uil-moon"></i> <!-- Icon for theme toggle (moon for dark mode) -->
            </button>
        </div>
    </nav>
</header>

<script>
document.getElementById('searchToggle').addEventListener('click', function() {
    var dropdown = document.getElementById('searchDropdown');
    dropdown.style.display = dropdown.style.display === 'none' || dropdown.style.display === '' ? 'block' : 'none';
});

// BY MUSTFA DEV
window.onclick = function(event) {
    if (!event.target.matches('#searchToggle')) {
        var dropdowns = document.getElementsByClassName("search-dropdown");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.style.display === 'block') {
                openDropdown.style.display = 'none';
            }
        }
    }
}
</script>