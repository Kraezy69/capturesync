// Add notification styles to the page
const style = document.createElement('style');
style.textContent = `
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 25px;
    border-radius: 4px;
    color: white;
    font-weight: bold;
    z-index: 1000;
    animation: slideIn 0.5s ease-in-out;
}

.notification.success {
    background-color: #4CAF50;
}

.notification.error {
    background-color: #f44336;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}
`;
document.head.appendChild(style);

// DOM Elements
const modal = document.querySelector('.modal');
const addPhotographerBtn = document.querySelector('.add-btn');
const closeModalBtn = document.querySelectorAll('.modal .close');
const addPhotographerForm = document.querySelector('#addPhotographerForm');
const editModal = document.getElementById('editModal');
const editPhotographerForm = document.getElementById('editPhotographerForm');
const menuIcon = document.querySelector('.menu-icon');
const dropdownMenu = document.querySelector('.dropdown-menu');

// Toggle dropdown menu
menuIcon.addEventListener('click', () => {
    dropdownMenu.classList.toggle('active');
});

// Close dropdown when clicking outside
document.addEventListener('click', (e) => {
    if (!menuIcon.contains(e.target) && !dropdownMenu.contains(e.target)) {
        dropdownMenu.classList.remove('active');
    }
});

// Modal Functions
function openAddModal() {
    // Always close edit modal before opening add modal
    editModal.style.display = 'none';
    modal.style.display = 'block';
    addPhotographerForm.reset();
    document.querySelector('#addModal .modal-title').textContent = 'Add New Photographer';
}
function openEditModal() {
    // Always close add modal before opening edit modal
    modal.style.display = 'none';
    editModal.style.display = 'block';
    editPhotographerForm.reset();
    document.querySelector('#editModal .modal-title').textContent = 'Edit Photographer';
}
function closeAllModals() {
    modal.style.display = 'none';
    editModal.style.display = 'none';
}

// Event Listeners for Modal
addPhotographerBtn.addEventListener('click', openAddModal);
closeModalBtn.forEach(btn => btn.addEventListener('click', closeAllModals));
window.addEventListener('click', (e) => {
    if (e.target === modal || e.target === editModal) {
        closeAllModals();
    }
});

// Add Photographer Form Submission
addPhotographerForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(addPhotographerForm);
    try {
        const response = await fetch('add_photographer.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        if (data.success) {
            closeAllModals();
            showNotification(data.message, 'success');
            refreshPhotographersList();
        } else {
            showNotification(data.message, 'error');
        }
    } catch (error) {
        showNotification('An error occurred. Please try again.', 'error');
        console.error('Error:', error);
    }
});

// Edit Photographer
async function editPhotographer(id) {
    try {
        const response = await fetch(`get_photographer.php?id=${id}`);
        const data = await response.json();
        if (data.success) {
            openEditModal();
            // Fill edit form with photographer data
            editPhotographerForm.querySelector('[name="photographer_id"]').value = id;
            editPhotographerForm.querySelector('[name="fullname"]').value = data.photographer.fullname;
            editPhotographerForm.querySelector('[name="email"]').value = data.photographer.email;
            editPhotographerForm.querySelector('[name="specialty"]').value = data.photographer.specialty;
            editPhotographerForm.querySelector('[name="experience"]').value = data.photographer.experience;
            editPhotographerForm.querySelector('[name="location"]').value = data.photographer.location;
            editPhotographerForm.querySelector('[name="portfolio"]').value = data.photographer.portfolio;
            editPhotographerForm.querySelector('[name="password"]').value = '';
            // Set status dropdown
            editPhotographerForm.querySelector('[name="status"]').value = data.photographer.status || 'active';
        } else {
            showNotification(data.message, 'error');
        }
    } catch (error) {
        showNotification('An error occurred. Please try again.', 'error');
        console.error('Error:', error);
    }
}

// Edit Photographer Form Submission
editPhotographerForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(editPhotographerForm);
    try {
        const response = await fetch('edit_photographer.php', {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        if (data.success) {
            closeAllModals();
            showNotification(data.message, 'success');
            refreshPhotographersList();
        } else {
            showNotification(data.message, 'error');
        }
    } catch (error) {
        showNotification('An error occurred. Please try again.', 'error');
        console.error('Error:', error);
    }
});

// Delete Photographer
async function deletePhotographer(id) {
    if (!confirm('Are you sure you want to delete this photographer?')) {
        return;
    }
    
    try {
        const response = await fetch('delete_photographer.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: id })
        });
        
        const data = await response.json();
        
        if (data.success) {
            showNotification(data.message, 'success');
            refreshPhotographersList();
        } else {
            showNotification(data.message, 'error');
        }
    } catch (error) {
        showNotification('An error occurred. Please try again.', 'error');
        console.error('Error:', error);
    }
}

// Refresh Photographers List
async function refreshPhotographersList() {
    try {
        const response = await fetch('get_photographer.php');
        const data = await response.json();
        
        if (data.success) {
            const tableBody = document.querySelector('.photographers-table tbody');
            tableBody.innerHTML = '';
            
            if (!Array.isArray(data.photographers)) {
                showNotification('Photographers data is missing or invalid.', 'error');
                return;
            }
            
            data.photographers.forEach(photographer => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${photographer.fullname}</td>
                    <td>${photographer.email}</td>
                    <td>${photographer.specialty}</td>
                    <td>${photographer.experience} years</td>
                    <td>${photographer.location}</td>
                    <td>
                        <div class="actions">
                            <button class="edit-btn" onclick="editPhotographer(${photographer.id})">Edit</button>
                            <button class="delete-btn" onclick="deletePhotographer(${photographer.id})">Delete</button>
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);
            });
            
            // Update statistics
            updateStatistics(data.stats);
        } else {
            showNotification(data.message, 'error');
        }
    } catch (error) {
        showNotification('An error occurred while refreshing the list.', 'error');
        console.error('Error:', error);
    }
}

// Update Statistics
function updateStatistics(stats) {
    const totalPhotographersElem = document.querySelector('#total-photographers');
    if (totalPhotographersElem) totalPhotographersElem.textContent = stats.total_photographers;
    const totalUsersElem = document.querySelector('#total-users');
    if (totalUsersElem) totalUsersElem.textContent = stats.total_users;
    const totalBookingsElem = document.querySelector('#total-bookings');
    if (totalBookingsElem) totalBookingsElem.textContent = stats.total_bookings;
    const totalSalesElem = document.querySelector('#total-sales');
    if (totalSalesElem) totalSalesElem.textContent = stats.total_sales;
}

// Fetch Statistics
async function fetchStatistics() {
    try {
        const response = await fetch('get_statistics.php');
        const data = await response.json();
        
        if (data.success) {
            updateStatistics(data.stats);
        } else {
            showNotification(data.message, 'error');
        }
    } catch (error) {
        showNotification('An error occurred while fetching statistics.', 'error');
        console.error('Error:', error);
    }
}

// Notification System
function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.style.animation = 'slideIn 0.5s ease-in-out reverse';
        setTimeout(() => {
            document.body.removeChild(notification);
        }, 500);
    }, 3000);
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    refreshPhotographersList();
    fetchStatistics();
    // Refresh statistics every 5 minutes
    setInterval(fetchStatistics, 300000);
}); 

// Sidebar navigation logic
const sidebarBtns = document.querySelectorAll('.sidebar-btn');
const sidebarSections = document.querySelectorAll('.sidebar-section');

sidebarBtns.forEach(btn => {
    btn.addEventListener('click', function() {
        // Remove active from all buttons
        sidebarBtns.forEach(b => b.classList.remove('active'));
        // Hide all sections
        sidebarSections.forEach(section => section.style.display = 'none');
        // Add active to clicked button
        this.classList.add('active');
        // Show the corresponding section
        const sectionId = this.getAttribute('data-section');
        document.getElementById(sectionId).style.display = 'block';
    });
}); 