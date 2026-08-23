<?php
session_start();

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../php/db.php";

// ================================
// FETCH CONTACT MESSAGES
// ================================

$sql = "SELECT id, name, email, subject, message, created_at
        FROM contact_messages
        ORDER BY id DESC";

$result = $conn->query($sql);

$messages = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}

// ================================
// DASHBOARD STATS
// ================================

$totalMessages = count($messages);

$latestMessage = "--";

if ($totalMessages > 0) {
    $latestMessage = $messages[0]["name"];
}

// Today's messages
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

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >

    <!-- Admin CSS -->
    <link rel="stylesheet" href="admin.css">

</head>

<body>

<!-- =====================================================
     SIDEBAR
===================================================== -->

<aside class="sidebar">

    <div class="sidebar-logo">

        <i class="fa-solid fa-user-shield"></i>

        <span>Admin Panel</span>

    </div>


    <nav class="sidebar-nav">

        <!-- Dashboard -->
        <a href="dashboard.php" class="active">

            <i class="fa-solid fa-gauge-high"></i>

            Dashboard

        </a>


        <!-- Messages -->
        <a href="#messages">

            <i class="fa-solid fa-envelope"></i>

            Messages

        </a>


        <!-- Portfolio -->
        <a href="../index.html" target="_blank">

            <i class="fa-solid fa-globe"></i>

            View Portfolio

        </a>

    </nav>


    <!-- Logout -->

    <div class="sidebar-bottom">

        <a href="logout.php" class="logout-btn">

            <i class="fa-solid fa-right-from-bracket"></i>

            Logout

        </a>

    </div>

</aside>


<!-- =====================================================
     MAIN CONTENT
===================================================== -->

<main class="main-content">


    <!-- ================= TOP BAR ================= -->

    <header class="topbar">

        <div>

            <h1>Dashboard</h1>

            <p>
                Welcome back,
                <?php echo htmlspecialchars($_SESSION["admin_name"] ?? "Admin"); ?>
                👋
            </p>

        </div>


        <div class="admin-profile">

            <div class="profile-icon">

                <i class="fa-solid fa-user"></i>

            </div>


            <div>

                <strong>
                    <?php echo htmlspecialchars($_SESSION["admin_name"] ?? "Administrator"); ?>
                </strong>

                <small>Admin</small>

            </div>

        </div>

    </header>



    <!-- =================================================
         STAT CARDS
    ================================================== -->

    <section class="stats">


        <!-- TOTAL MESSAGES -->

        <div class="stat-card">

            <div class="stat-icon">

                <i class="fa-solid fa-envelope"></i>

            </div>


            <div>

                <span>Total Messages</span>

                <h2>
                    <?php echo $totalMessages; ?>
                </h2>

            </div>

        </div>



        <!-- LATEST MESSAGE -->

        <div class="stat-card">

            <div class="stat-icon">

                <i class="fa-solid fa-envelope-open"></i>

            </div>


            <div>

                <span>Latest Message</span>

                <h2>

                    <?php echo htmlspecialchars($latestMessage); ?>

                </h2>

            </div>

        </div>



        <!-- TODAY -->

        <div class="stat-card">

            <div class="stat-icon">

                <i class="fa-solid fa-calendar-days"></i>

            </div>


            <div>

                <span>Today</span>

                <h2>
                    <?php echo $todayMessages; ?>
                </h2>

            </div>

        </div>

    </section>



    <!-- =================================================
         CONTACT MESSAGES
    ================================================== -->

    <section class="messages-section" id="messages">


        <!-- SECTION HEADER -->

        <div class="section-header">

            <div>

                <h2>Contact Messages</h2>

                <p>
                    Messages received from your portfolio.
                </p>

            </div>


            <button
                class="refresh-btn"
                onclick="location.reload();"
            >

                <i class="fa-solid fa-rotate"></i>

                Refresh

            </button>

        </div>



        <!-- SEARCH -->

        <div class="search-box">

            <i class="fa-solid fa-magnifying-glass"></i>

            <input
                type="text"
                id="searchInput"
                placeholder="Search messages..."
            >

        </div>



        <!-- =================================================
             MESSAGE TABLE
        ================================================== -->

        <div class="table-container">

            <table>

                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Name</th>

                        <th>Email</th>

                        <th>Subject</th>

                        <th>Message</th>

                        <th>Date</th>

                        <th>Action</th>

                    </tr>

                </thead>


                <tbody id="messagesTable">


                <?php if (count($messages) > 0): ?>


                    <?php foreach ($messages as $message): ?>

                        <tr class="message-row">


                            <!-- ID -->

                            <td>

                                <?php
                                echo (int)$message["id"];
                                ?>

                            </td>


                            <!-- NAME -->

                            <td>

                                <?php
                                echo htmlspecialchars($message["name"]);
                                ?>

                            </td>


                            <!-- EMAIL -->

                            <td>

                                <?php
                                echo htmlspecialchars($message["email"]);
                                ?>

                            </td>


                            <!-- SUBJECT -->

                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $message["subject"] ?: "-"
                                );
                                ?>

                            </td>


                            <!-- MESSAGE -->

                            <td class="message-preview">

                                <?php

                                $shortMessage =
                                    strlen($message["message"]) > 60
                                    ? substr($message["message"], 0, 60) . "..."
                                    : $message["message"];

                                echo htmlspecialchars($shortMessage);

                                ?>

                            </td>


                            <!-- DATE -->

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $message["created_at"]
                                );

                                ?>

                            </td>


                            <!-- ACTION -->

                            <td>

                                <button
                                    class="view-btn"
                                    onclick="viewMessage(
                                        <?php echo (int)$message['id']; ?>
                                    )"
                                >

                                    <i class="fa-solid fa-eye"></i>

                                    View

                                </button>

                            </td>

                        </tr>


                        <!-- Hidden message data -->

                        <div
                            id="message-<?php echo (int)$message['id']; ?>"
                            class="hidden-message"
                            data-name="<?php echo htmlspecialchars($message['name'], ENT_QUOTES); ?>"
                            data-email="<?php echo htmlspecialchars($message['email'], ENT_QUOTES); ?>"
                            data-subject="<?php echo htmlspecialchars($message['subject'], ENT_QUOTES); ?>"
                            data-date="<?php echo htmlspecialchars($message['created_at'], ENT_QUOTES); ?>"
                            data-message="<?php echo htmlspecialchars($message['message'], ENT_QUOTES); ?>"
                        ></div>


                    <?php endforeach; ?>


                <?php else: ?>


                    <tr>

                        <td colspan="7" class="no-messages">

                            <i class="fa-solid fa-inbox"></i>

                            <h3>No Messages Found</h3>

                            <p>
                                Your contact form messages will appear here.
                            </p>

                        </td>

                    </tr>


                <?php endif; ?>


                </tbody>

            </table>

        </div>

    </section>

</main>



<!-- =====================================================
     MESSAGE MODAL
===================================================== -->

<div
    id="messageModal"
    class="modal"
    style="display:none;"
>


    <div class="modal-content">


        <!-- Close -->

        <button
            id="closeModal"
            class="close-modal"
        >

            <i class="fa-solid fa-xmark"></i>

        </button>


        <h2>

            <i class="fa-solid fa-envelope-open-text"></i>

            Message Details

        </h2>



        <div class="message-details">


            <p>

                <strong>Name:</strong>

                <span id="modalName"></span>

            </p>


            <p>

                <strong>Email:</strong>

                <span id="modalEmail"></span>

            </p>


            <p>

                <strong>Subject:</strong>

                <span id="modalSubject"></span>

            </p>


            <p>

                <strong>Date:</strong>

                <span id="modalDate"></span>

            </p>


            <div class="full-message">

                <strong>Message:</strong>

                <p id="modalMessage"></p>

            </div>


        </div>

    </div>

</div>



<!-- =====================================================
     JAVASCRIPT
===================================================== -->

<script>

const searchInput =
    document.getElementById("searchInput");

const rows =
    document.querySelectorAll(".message-row");


// =====================================================
// SEARCH
// =====================================================

if (searchInput) {

    searchInput.addEventListener("input", function () {

        const search =
            this.value.toLowerCase().trim();


        rows.forEach(function (row) {

            const text =
                row.textContent.toLowerCase();


            if (text.includes(search)) {

                row.style.display = "";

            } else {

                row.style.display = "none";

            }

        });

    });

}



// =====================================================
// VIEW MESSAGE
// =====================================================

function viewMessage(id) {

    const data =
        document.getElementById("message-" + id);


    if (!data) {
        return;
    }


    document.getElementById("modalName").textContent =
        data.dataset.name;


    document.getElementById("modalEmail").textContent =
        data.dataset.email;


    document.getElementById("modalSubject").textContent =
        data.dataset.subject || "-";


    document.getElementById("modalDate").textContent =
        data.dataset.date;


    document.getElementById("modalMessage").textContent =
        data.dataset.message;


    document.getElementById("messageModal").style.display =
        "flex";

}



// =====================================================
// CLOSE MODAL
// =====================================================

document
    .getElementById("closeModal")
    .addEventListener("click", function () {

        document.getElementById("messageModal").style.display =
            "none";

    });


// Click outside modal

document
    .getElementById("messageModal")
    .addEventListener("click", function (event) {

        if (event.target === this) {

            this.style.display = "none";

        }

    });

</script>


</body>

</html>

<?php

$conn->close();

?>