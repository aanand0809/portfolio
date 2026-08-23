<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../php/db.php";

// Fetch contact messages
$sql = "SELECT id, name, email, subject, message, created_at FROM contact_messages ORDER BY id DESC";
$result = $conn->query($sql);

$messages = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}

// Dashboard statistics
$totalMessages = count($messages);
$latestSender = $totalMessages > 0 ? $messages[0]["name"] : "None";

$todayMessages = 0;
$today = date("Y-m-d");
foreach ($messages as $msg) {
    if (date("Y-m-d", strtotime($msg["created_at"])) === $today) {
        $todayMessages++;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Anand Kumar</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Admin CSS -->
    <link rel="stylesheet" href="admin.css">
</head>

<body>

<!-- ================= SIDEBAR ================= -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <i class="fa-solid fa-user-shield"></i>
        <span>Admin Panel</span>
    </div>

    <nav class="sidebar-nav">
        <a href="dashboard.php" class="active">
            <i class="fa-solid fa-gauge-high"></i>
            <span>Dashboard</span>
        </a>
        <a href="#messages">
            <i class="fa-solid fa-envelope"></i>
            <span>Messages</span>
        </a>
        <a href="../index.html" target="_blank">
            <i class="fa-solid fa-globe"></i>
            <span>View Portfolio</span>
        </a>
    </nav>

    <div class="sidebar-bottom">
        <a href="logout.php" class="logout-btn">
            <i class="fa-solid fa-right-from-bracket"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>

<!-- ================= MAIN CONTENT ================= -->
<main class="main-content">
    <!-- Top Bar -->
    <header class="topbar">
        <div>
            <h1>Dashboard</h1>
            <p>Welcome back, <strong><?php echo htmlspecialchars($_SESSION["admin_name"] ?? "Admin"); ?></strong> 👋</p>
        </div>

        <div class="admin-profile">
            <div class="profile-icon">
                <i class="fa-solid fa-user"></i>
            </div>
            <div>
                <strong><?php echo htmlspecialchars($_SESSION["admin_name"] ?? "Administrator"); ?></strong>
                <small>Admin</small>
            </div>
        </div>
    </header>

    <!-- Stats Cards -->
    <section class="stats">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-envelope"></i>
            </div>
            <div>
                <span>Total Messages</span>
                <h2><?php echo $totalMessages; ?></h2>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-envelope-open-text"></i>
            </div>
            <div>
                <span>Latest Message From</span>
                <h2 style="font-size: 20px;"><?php echo htmlspecialchars($latestSender); ?></h2>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fa-solid fa-calendar-day"></i>
            </div>
            <div>
                <span>Received Today</span>
                <h2><?php echo $todayMessages; ?></h2>
            </div>
        </div>
    </section>

    <!-- Messages Section -->
    <section class="messages-section" id="messages">
        <div class="section-header">
            <div>
                <h2>Contact Form Messages</h2>
                <p>Real-time database records of inquiries submitted through your portfolio.</p>
            </div>

            <button class="refresh-btn" onclick="location.reload();">
                <i class="fa-solid fa-rotate"></i>
                <span>Refresh</span>
            </button>
        </div>

        <!-- Search Box -->
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" id="searchInput" placeholder="Search by name, email, or subject...">
        </div>

        <!-- Message Table -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Subject</th>
                        <th>Message Preview</th>
                        <th>Date Received</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="messagesTable">
                    <?php if (count($messages) > 0): ?>
                        <?php foreach ($messages as $message): ?>
                            <tr class="message-row"
                                data-id="<?php echo (int)$message['id']; ?>"
                                data-name="<?php echo htmlspecialchars($message['name'], ENT_QUOTES); ?>"
                                data-email="<?php echo htmlspecialchars($message['email'], ENT_QUOTES); ?>"
                                data-subject="<?php echo htmlspecialchars($message['subject'] ?: 'No Subject', ENT_QUOTES); ?>"
                                data-date="<?php echo htmlspecialchars($message['created_at'], ENT_QUOTES); ?>"
                                data-message="<?php echo htmlspecialchars($message['message'], ENT_QUOTES); ?>">
                                <td><strong>#<?php echo (int)$message['id']; ?></strong></td>
                                <td><strong><?php echo htmlspecialchars($message['name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($message['email']); ?></td>
                                <td><?php echo htmlspecialchars($message['subject'] ?: '-'); ?></td>
                                <td class="message-preview">
                                    <?php 
                                    $preview = strlen($message["message"]) > 55 ? substr($message["message"], 0, 55) . "..." : $message["message"];
                                    echo htmlspecialchars($preview);
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($message['created_at']); ?></td>
                                <td>
                                    <button class="view-btn view-trigger">
                                        <i class="fa-solid fa-eye"></i>
                                        <span>View</span>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="no-messages">
                                <i class="fa-solid fa-inbox"></i>
                                <h3>No Messages Yet</h3>
                                <p>Inquiries submitted via your portfolio will be displayed here.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<!-- Message Details Modal -->
<div id="messageModal" class="modal" aria-hidden="true" role="dialog">
    <div class="modal-content">
        <button id="closeModal" class="close-modal" aria-label="Close modal">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <h2>
            <i class="fa-solid fa-envelope-open"></i>
            <span>Message Details</span>
        </h2>

        <div class="message-details">
            <p><strong>Name:</strong> <span id="modalName"></span></p>
            <p><strong>Email:</strong> <span id="modalEmail"></span></p>
            <p><strong>Subject:</strong> <span id="modalSubject"></span></p>
            <p><strong>Date:</strong> <span id="modalDate"></span></p>

            <div class="full-message">
                <strong>Full Message:</strong>
                <p id="modalMessage"></p>
            </div>

            <div style="margin-top: 20px; display: flex; justify-content: flex-end;">
                <a id="modalReplyBtn" href="#" class="refresh-btn" style="background: var(--accent-gradient);">
                    <i class="fa-solid fa-reply"></i>
                    <span>Reply via Email</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Search Filtering
    const searchInput = document.getElementById("searchInput");
    const rows = document.querySelectorAll(".message-row");

    if (searchInput) {
        searchInput.addEventListener("input", function () {
            const query = this.value.toLowerCase().trim();
            rows.forEach((row) => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(query) ? "" : "none";
            });
        });
    }

    // Modal Handling
    const modal = document.getElementById("messageModal");
    const closeModalBtn = document.getElementById("closeModal");
    const modalName = document.getElementById("modalName");
    const modalEmail = document.getElementById("modalEmail");
    const modalSubject = document.getElementById("modalSubject");
    const modalDate = document.getElementById("modalDate");
    const modalMessage = document.getElementById("modalMessage");
    const modalReplyBtn = document.getElementById("modalReplyBtn");

    function openMessageModal(row) {
        if (!modal) return;
        const name = row.getAttribute("data-name");
        const email = row.getAttribute("data-email");
        const subject = row.getAttribute("data-subject");
        const date = row.getAttribute("data-date");
        const message = row.getAttribute("data-message");

        modalName.textContent = name;
        modalEmail.textContent = email;
        modalSubject.textContent = subject;
        modalDate.textContent = date;
        modalMessage.textContent = message;
        modalReplyBtn.href = `mailto:${email}?subject=Re: ${encodeURIComponent(subject)}`;

        modal.classList.add("active");
        modal.setAttribute("aria-hidden", "false");
    }

    function closeMessageModal() {
        if (!modal) return;
        modal.classList.remove("active");
        modal.setAttribute("aria-hidden", "true");
    }

    document.querySelectorAll(".view-trigger").forEach((btn) => {
        btn.addEventListener("click", (e) => {
            const row = e.target.closest(".message-row");
            if (row) {
                openMessageModal(row);
            }
        });
    });

    if (closeModalBtn) {
        closeModalBtn.addEventListener("click", closeMessageModal);
    }

    if (modal) {
        modal.addEventListener("click", (e) => {
            if (e.target === modal) {
                closeMessageModal();
            }
        });
    }

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            closeMessageModal();
        }
    });
</script>

</body>
</html>
<?php
$conn->close();
?>