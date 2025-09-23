// Sidebar Toggle
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const mainContent = document.getElementById("main-content");
    const isMobile = window.matchMedia("(max-width: 768px)").matches;

    if (!sidebar || !mainContent) return;

    if (isMobile) {
        // On mobile behave like off-canvas
        sidebar.classList.toggle("show");
        document.body.classList.toggle("sidebar-open");
        const backdrop = document.getElementById("sidebar-backdrop");
        if (backdrop) backdrop.classList.toggle("show");
        // Do not store collapsed state for mobile
        return;
    }

    // Desktop/tablet collapse
    sidebar.classList.toggle("collapsed");
    mainContent.classList.toggle("expanded");

    const isCollapsed = sidebar.classList.contains("collapsed");
    localStorage.setItem("sidebarCollapsed", isCollapsed);
}

// Load sidebar state from localStorage
function loadSidebarState() {
    const sidebar = document.getElementById("sidebar");
    const mainContent = document.getElementById("main-content");
    const isCollapsed = localStorage.getItem("sidebarCollapsed") === "true";
    const isMobile = window.matchMedia("(max-width: 768px)").matches;

    if (!sidebar || !mainContent) return;

    // Ignore persisted collapse on mobile
    if (!isMobile && isCollapsed) {
        sidebar.classList.add("collapsed");
        mainContent.classList.add("expanded");
    }
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
    console.log("DOM loaded, initializing sidebar...");
    loadSidebarState();

    // Add click event listener to sidebar toggle (navbar)
    const sidebarToggle = document.getElementById("sidebarToggle");
    if (sidebarToggle) {
        console.log("Navbar toggle button found");
        sidebarToggle.addEventListener("click", function (e) {
            e.preventDefault();
            console.log("Navbar toggle clicked");
            toggleSidebar();
        });
    } else {
        console.log("Navbar toggle button NOT found");
    }

    // Create mobile backdrop if not present
    if (!document.getElementById("sidebar-backdrop")) {
        const backdrop = document.createElement("div");
        backdrop.id = "sidebar-backdrop";
        backdrop.className = "sidebar-backdrop";
        document.body.appendChild(backdrop);
        backdrop.addEventListener("click", () => {
            const sidebar = document.getElementById("sidebar");
            if (sidebar && sidebar.classList.contains("show")) {
                toggleSidebar();
            }
        });
    }

    // Close mobile sidebar on resize to desktop
    window.addEventListener("resize", () => {
        const sidebar = document.getElementById("sidebar");
        const mainContent = document.getElementById("main-content");
        const backdrop = document.getElementById("sidebar-backdrop");
        const isMobile = window.matchMedia("(max-width: 768px)").matches;
        if (!isMobile && sidebar) {
            sidebar.classList.remove("show");
            document.body.classList.remove("sidebar-open");
            if (backdrop) backdrop.classList.remove("show");
            // Ensure main content respects collapsed state
            const collapsed = localStorage.getItem("sidebarCollapsed") === "true";
            mainContent?.classList.toggle("expanded", collapsed);
            sidebar.classList.toggle("collapsed", collapsed);
        }
    });
});

// Theme Toggle
document.getElementById("themeToggle").addEventListener("click", function () {
    const html = document.documentElement;
    const themeIcon = document.getElementById("themeIcon");

    if (html.getAttribute("data-bs-theme") === "dark") {
        html.setAttribute("data-bs-theme", "light");
        themeIcon.className = "bi bi-sun-fill";
        localStorage.setItem("theme", "light");
    } else {
        html.setAttribute("data-bs-theme", "dark");
        themeIcon.className = "bi bi-moon-fill";
        localStorage.setItem("theme", "dark");
    }
});

// Load saved theme
const savedTheme = localStorage.getItem("theme") || "light";
document.documentElement.setAttribute("data-bs-theme", savedTheme);
if (savedTheme === "dark") {
    document.getElementById("themeIcon").className = "bi bi-moon-fill";
}

// Auto-hide alerts
setTimeout(function () {
    const alerts = document.querySelectorAll(".alert");
    alerts.forEach(function (alert) {
        const bsAlert = new bootstrap.Alert(alert);
        bsAlert.close();
    });
}, 5000);

// Global functions for forms
function confirmDelete(
    url,
    title = "Are you sure?",
    text = "This action cannot be undone!"
) {
    Swal.fire({
        title: title,
        text: text,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            // Create a form and submit it
            const form = document.createElement("form");
            form.method = "POST";
            form.action = url;

            const csrfToken = document.createElement("input");
            csrfToken.type = "hidden";
            csrfToken.name = "_token";
            csrfToken.value = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            const methodField = document.createElement("input");
            methodField.type = "hidden";
            methodField.name = "_method";
            methodField.value = "DELETE";

            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function showToast(message, type = "info", position = "bottom-end", duration = 3000) {

    let bgColor = "#3498db"; // default blue
    let barColor = "#2980b9"; // default progress bar

    if (type === "success") { bgColor = "#2ecc71"; barColor = "#27ae60"; } // green
    if (type === "error")   { bgColor = "#e74c3c"; barColor = "#c0392b"; } // red
    if (type === "warning") { bgColor = "#f39c12"; barColor = "#d35400"; } // orange
    if (type === "info")    { bgColor = "#3498db"; barColor = "#2980b9"; } // blue

    Swal.fire({
        toast: true,
        position: position,
        icon: type,
        title: message,
        showConfirmButton: false,
        timer: duration,
        timerProgressBar: true,
        showCloseButton: true,
        background: bgColor,
        didOpen: (toast) => {
            toast.addEventListener("mouseenter", Swal.stopTimer);
            toast.addEventListener("mouseleave", Swal.resumeTimer);
        },
        color: "#fff",
        customClass: {
            popup: `colored-toast-${type}`
        }
    });
}
