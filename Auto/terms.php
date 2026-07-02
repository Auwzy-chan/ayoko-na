<?php
require_once __DIR__ . '/auth.php';
requireLogin();

// Dynamic terms data layout matching your dashboard sections
$terms_sections = [
    [
        "id" => 1,
        "title" => "1. Acceptance of Terms",
        "content" => "By accessing and using the Auto Hub platform, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions. If you do not agree, please discontinue use immediately. These terms apply to all users, visitors, and customers of Auto Hub.",
        "default_open" => true // Matches screenshot where section 1 is expanded
    ],
    [
        "id" => 2,
        "title" => "2. Vehicle Pricing and Availability",
        "content" => "All prices listed on our platform are subject to change without prior notice. While we strive to maintain real-time accuracy regarding inventory levels, vehicle availability is non-binding until explicitly confirmed via a signed purchase order or formalized deposit arrangement with our authorized sales desk.",
        "default_open" => false
    ],
    [
        "id" => 3,
        "title" => "3. Booking and Reservations",
        "content" => "Reserving a vehicle online holds the unit for a maximum window of 48 hours. If a structural down payment or finance validation is not authorized within this grace period, the reservation automatically expires, and the vehicle will return to our public full inventory stream.",
        "default_open" => false
    ],
    [
        "id" => 4,
        "title" => "4. Financing and Payments",
        "content" => "Estimated monthly totals rendered via our internal Financing Calculator utilize tier-1 baseline credit figures. Definitive legal finance configurations, explicit percentage rate assignments, and loan terms remain conditional based upon structural underwriting verifications.",
        "default_open" => false
    ],
    [
        "id" => 5,
        "title" => "5. Discounts and Promotions",
        "content" => "Promo codes, exclusive seasonal benefits, or special fleet tokens available via our portal must be processed directly at checkout. Discounts are non-transferable, carry standalone restrictions, and cannot be stacked unless explicitly authorized.",
        "default_open" => false
    ],
    [
        "id" => 6,
        "title" => "6. Privacy and Data",
        "content" => "Your absolute digital data protection represents a top operational parameter. Account registrations, submitted application criteria, phone numbers, and profile parameters are secured and processed strictly within compliance boundary regulations.",
        "default_open" => false
    ],
    [
        "id" => 7,
        "title" => "7. Warranty and Returns",
        "content" => "Premium handpicked certified pre-owned units carry specified coverage guidelines. Standard vehicle acquisitions retain designated inspection windows alongside structural limited warranties detailed within separate mechanical packets.",
        "default_open" => false
    ],
    [
        "id" => 8,
        "title" => "8. Limitation of Liability",
        "content" => "Auto Hub shall not be held accountable for any indirect, unintended, or situational structural losses, data drop incidents, or mechanical lifecycle variances arising out of continuous operations beyond explicit guarantee agreements.",
        "default_open" => false
    ],
    [
        "id" => 9,
        "title" => "9. Governing Law",
        "content" => "These structural operating conditions, standard usage boundaries, and transactional interactions remain managed under local corporate jurisdictional statutes without provoking statutory grid conflictions.",
        "default_open" => false
    ],
    [
        "id" => 10,
        "title" => "10. Contact Information",
        "content" => "For any inquiries regarding your lifecycle journey, contract configurations, or baseline platform access, please reach out to our active administrative group via help@autohub-premium.com.",
        "default_open" => false
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Hub - Terms & Conditions</title>
    <style>
        :root {
            --bg-dark: #070a12;
            --panel-dark: #0f1422;
            --card-bg: #131929;
            --accent-orange: #ff9f05;
            --text-muted: #535d73;
            --text-light: #a0a5b5;
            --border-color: #1f283e;
            --accordion-hover: #182035;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--bg-dark);
            color: #ffffff;
            padding-top: 90px;
            min-height: 100vh;
        }

        /* Navigation Header */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 6%;
            background-color: rgba(7, 10, 18, 0.95);
            backdrop-filter: blur(10px);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 100;
            border-bottom: 1px solid var(--border-color);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-icon {
            background-color: var(--accent-orange);
            color: #000;
            padding: 6px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-name {
            font-size: 1.1rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        nav {
            display: flex;
            gap: 30px;
        }

        nav a {
            color: var(--text-light);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }

        nav a.active {
            color: var(--accent-orange);
            border-bottom: 2px solid var(--accent-orange);
            padding-bottom: 4px;
        }

        .user-badge {
            background-color: #172033;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid var(--border-color);
        }

        .user-avatar {
            width: 18px;
            height: 18px;
            background-color: var(--accent-orange);
            color: #000;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.7rem;
        }

        /* Core Content Grid Wrapper */
        main {
            padding: 40px 6%;
            max-width: 800px;
            margin: 0 auto;
        }

        .terms-header h1 {
            font-size: 2rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .terms-header p {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-top: 4px;
            margin-bottom: 40px;
        }

        /* Accordion Custom Styling Block */
        .accordion-item {
            background-color: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.02);
            border-radius: 8px;
            margin-bottom: 12px;
            overflow: hidden;
            transition: border-color 0.2s;
        }

        .accordion-item.active-item {
            border-color: var(--border-color);
        }

        .accordion-trigger {
            width: 100%;
            padding: 20px 24px;
            background: transparent;
            border: none;
            color: #ffffff;
            font-size: 0.95rem;
            font-weight: 600;
            text-align: left;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.1s;
        }

        .accordion-trigger:hover {
            background-color: var(--accordion-hover);
        }

        .accordion-icon {
            color: var(--text-muted);
            transition: transform 0.2s ease;
        }

        /* Expanded content state tracking variables */
        .accordion-item.active-item .accordion-icon {
            transform: rotate(180deg);
        }

        .accordion-panel {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.25s ease-out;
            background-color: rgba(7, 10, 18, 0.15);
        }

        .accordion-content {
            padding: 0 24px 24px 24px;
            color: var(--text-light);
            font-size: 0.85rem;
            line-height: 1.6;
        }

        /* Floating Help Box */
        .help-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #1f283e;
            color: var(--text-muted);
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

    <header>
        <div class="brand">
            <div class="brand-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
            </div>
            <div class="brand-name">Auto Hub</div>
        </div>
        <nav>
            <a href="home.php">Home</a>
            <a href="Inventory.php">Inventory</a>
            <a href="Finance.php">Finance</a>
            <a href="offer.php">Offers</a>
            <a href="terms.php" class="active">Terms</a>
        </nav>
        <div class="user-badge">
            <span class="user-avatar"><?php echo htmlspecialchars(currentUserInitial()); ?></span>
            <span><?php echo htmlspecialchars(currentUserName()); ?></span>
        </div>
    </header>

    <main>
        <div class="terms-header">
            <h1>Terms & Conditions</h1>
            <p>Last updated June 1, 2024 • Please read carefully before using our services</p>
        </div>

        <div class="accordion-group">
            <?php foreach ($terms_sections as $section): ?>
                <div class="accordion-item <?php echo $section['default_open'] ? 'active-item' : ''; ?>">
                    <button class="accordion-trigger" onclick="toggleAccordion(this)">
                        <span><?php echo $section['title']; ?></span>
                        <span class="accordion-icon">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m6 9 6 6 6-6"/></svg>
                        </span>
                    </button>
                    <div class="accordion-panel" style="<?php echo $section['default_open'] ? 'max-height: 200px;' : ''; ?>">
                        <div class="accordion-content">
                            <?php echo $section['content']; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </main>

    <div class="help-icon">?</div>

    <script>
        function toggleAccordion(triggerElement) {
            const item = triggerElement.parentElement;
            const panel = triggerElement.nextElementSibling;
            
            // Check if current block is already expanded
            const isOpen = item.classList.contains('active-item');
            
            // Close active item tracking for clean, precise toggling
            if (isOpen) {
                panel.style.maxHeight = null;
                item.classList.remove('active-item');
            } else {
                panel.style.maxHeight = panel.scrollHeight + "px";
                item.classList.add('active-item');
            }
        }

        // Initialize correctly on load for items marked default_open
        window.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.accordion-item.active-item .accordion-panel').forEach(panel => {
                panel.style.maxHeight = panel.scrollHeight + "px";
            });
        });
    </script>
</body>
</html>
