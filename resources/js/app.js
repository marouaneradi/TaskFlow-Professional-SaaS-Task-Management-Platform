// TaskFlow — Main JavaScript
import './bootstrap';

// ── Theme Management ─────────────────────────────────────
const initTheme = () => {
    const stored = localStorage.getItem('taskflow-theme');
    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
    const theme = stored || (prefersDark ? 'dark' : 'light');
    document.documentElement.setAttribute('data-theme', theme);
    return theme;
};

const currentTheme = initTheme();

window.toggleTheme = () => {
    const current = document.documentElement.getAttribute('data-theme');
    const next = current === 'dark' ? 'light' : 'dark';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('taskflow-theme', next);

    // Update toggle buttons
    document.querySelectorAll('.theme-toggle').forEach(btn => {
        btn.classList.toggle('active', next === 'dark');
    });

    // Sync with server if logged in
    const form = document.getElementById('theme-sync-form');
    if (form) form.submit();
};

// ── Flash Messages ───────────────────────────────────────
window.closeFlash = (el) => {
    const flash = el.closest('.flash');
    flash.style.animation = 'slideOutRight 0.25s ease forwards';
    setTimeout(() => flash.remove(), 250);
};

const CSS_ANIM = `@keyframes slideOutRight { to { transform: translateX(110%); opacity: 0; } }`;
const style = document.createElement('style');
style.textContent = CSS_ANIM;
document.head.appendChild(style);

// Auto dismiss flash messages after 4s
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.flash').forEach(flash => {
        setTimeout(() => {
            if (flash.isConnected) {
                flash.style.animation = 'slideOutRight 0.25s ease forwards';
                setTimeout(() => flash.isConnected && flash.remove(), 250);
            }
        }, 4000);
    });
});

// ── Mobile Sidebar ───────────────────────────────────────
window.toggleSidebar = () => {
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.sidebar-overlay');
    sidebar?.classList.toggle('open');
    overlay?.classList.toggle('active');
};

document.addEventListener('click', (e) => {
    if (e.target.classList.contains('sidebar-overlay')) {
        window.toggleSidebar();
    }
});

// ── Dropdown Menus ───────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-dropdown]').forEach(trigger => {
        const menuId = trigger.getAttribute('data-dropdown');
        const menu = document.getElementById(menuId);
        if (!menu) return;

        trigger.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = !menu.classList.contains('hidden');
            // Close all other dropdowns
            document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
            if (!isOpen) menu.classList.remove('hidden');
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
    });
});

// ── Modals ───────────────────────────────────────────────
window.openModal = (id) => {
    const modal = document.getElementById(id);
    if (modal) {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
};

window.closeModal = (id) => {
    const modal = document.getElementById(id);
    if (modal) {
        modal.style.display = 'none';
        document.body.style.overflow = '';
    }
};

// Close modal on overlay click
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal-overlay')) {
        e.target.style.display = 'none';
        document.body.style.overflow = '';
    }
});

// Close modal on Escape
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay').forEach(m => {
            m.style.display = 'none';
        });
        document.body.style.overflow = '';
    }
});

// ── Confirm Dialogs ──────────────────────────────────────
window.confirmDelete = (message, formId) => {
    if (confirm(message || 'Are you sure you want to delete this?')) {
        document.getElementById(formId)?.submit();
    }
};

// ── Chart Animation ──────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const bars = document.querySelectorAll('.chart-bar[data-height]');
    bars.forEach(bar => {
        const height = bar.getAttribute('data-height');
        setTimeout(() => {
            bar.style.height = height + '%';
        }, 100);
    });

    // Progress ring animation
    const rings = document.querySelectorAll('.progress-ring-circle[data-progress]');
    rings.forEach(ring => {
        const progress = parseFloat(ring.getAttribute('data-progress'));
        const circumference = 2 * Math.PI * parseFloat(ring.getAttribute('r'));
        ring.style.strokeDasharray = circumference;
        const offset = circumference - (progress / 100) * circumference;
        setTimeout(() => {
            ring.style.strokeDashoffset = offset;
        }, 200);
    });
});

// ── Status Quick Update ──────────────────────────────────
window.updateTaskStatus = async (taskId, teamId, status, csrfToken) => {
    try {
        const response = await fetch(`/teams/${teamId}/tasks/${taskId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ status }),
        });

        if (response.ok) {
            window.location.reload();
        } else {
            alert('Failed to update status. Please try again.');
        }
    } catch (e) {
        console.error('Status update failed:', e);
    }
};

// ── Search Debounce ──────────────────────────────────────
let searchTimer;
window.debouncedSearch = (form, delay = 400) => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => form.submit(), delay);
};

// ── Init theme toggle state ──────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
    document.querySelectorAll('.theme-toggle').forEach(btn => {
        btn.classList.toggle('active', isDark);
    });
});
