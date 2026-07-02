<?php
require_once __DIR__ . '/auth.php';
requireLogin();

// Dynamic mock data array holding the promo details from your image
$offers = [
    [
        "id" => 1,
        "title" => "Fleet Purchase Discount",
        "description" => "10% off on purchases of 3 or more vehicles. Valid for registered business accounts.",
        "code" => "FLEET10",
        "badge" => "Business",
        "badge_style" => "color: #ff9f05; background: rgba(255, 159, 5, 0.1); border: 1px solid rgba(255, 159, 5, 0.2);",
        "percentage" => "10%",
        "expiry" => "Exp. December 31, 2026"
    ],
    [
        "id" => 2,
        "title" => "Summer Clearance Event",
        "description" => "8% off all SUVs and Trucks. Limited-time seasonal offer while stock lasts.",
        "code" => "SUMMER8",
        "badge" => "Seasonal",
        "badge_style" => "color: #e67e22; background: rgba(230, 126, 34, 0.1); border: 1px solid rgba(230, 126, 34, 0.2);",
        "percentage" => "8%",
        "expiry" => "Exp. August 31, 2026"
    ],
    [
        "id" => 3,
        "title" => "Go Electric Incentive",
        "description" => "5% off all Electric vehicles. Support the future of sustainable mobility.",
        "code" => "ELECTRIC5",
        "badge" => "EV",
        "badge_style" => "color: #2ecc71; background: rgba(46, 204, 113, 0.1); border: 1px solid rgba(46, 204, 113, 0.2);",
        "percentage" => "5%",
        "expiry" => "Exp. December 31, 2026"
    ],
    [
        "id" => 4,
        "title" => "Loyalty Reward",
        "description" => "Exclusive 15% off for returning customers on their second purchase.",
        "code" => "LOYAL15",
        "badge" => "Loyalty",
        "badge_style" => "color: #9b59b6; background: rgba(155, 89, 182, 0.1); border: 1px solid rgba(155, 89, 182, 0.2);",
        "percentage" => "15%",
        "expiry" => "Exp. June 30, 2027"
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Hub - Available Offers</title>
    <style>
        :root {
            --bg-dark: #070a12;
            --panel-dark: #0f1422;
            --card-bg: #131929;
            --accent-orange: #ff9f05;
            --text-muted: #4e586e;
            --text-light: #a0a5b5;
            --border-color: #1f283e;
            --input-bg: #111625;
            --blue-info-bg: #09172e;
            --blue-info-border: #102e5c;
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

        /* Offers Content Layout Container */
        main {
            padding: 40px 6%;
            max-width: 1100px;
            margin: 0 auto;
        }

        .offers-header h1 {
            font-size: 2rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .offers-header p {
            color: var(--text-light);
            font-size: 0.85rem;
            margin-top: 4px;
            margin-bottom: 40px;
        }

        /* Responsive Grid Distribution */
        .offers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(460px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        @media(max-width: 550px) {
            .offers-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Component Card Custom Styling */
        .offer-card {
            background-color: var(--card-bg);
            border: 1px solid rgba(255, 255, 255, 0.02);
            border-radius: 12px;
            padding: 28px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        /* Background large discount watermarks styling */
        .watermark-percentage {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 4rem;
            font-weight: 900;
            color: rgba(255, 255, 255, 0.03);
            line-height: 1;
            user-select: none;
            z-index: 1;
        }

        .offer-top-content {
            z-index: 2;
            position: relative;
        }

        .category-badge {
            display: inline-block;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 6px;
            margin-bottom: 18px;
        }

        .offer-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .offer-desc {
            color: var(--text-light);
            font-size: 0.85rem;
            line-height: 1.5;
            margin-bottom: 25px;
            max-width: 85%;
        }

        /* Action Code Blocks Group */
        .coupon-action-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
            z-index: 2;
            position: relative;
        }

        .code-container {
            display: flex;
            align-items: center;
            background-color: var(--input-bg);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 2px;
        }

        .code-text {
            font-family: 'Courier New', Courier, monospace;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 1px;
            padding: 8px 16px;
            color: #ffffff;
        }

        .copy-btn {
            background-color: transparent;
            border: none;
            color: var(--text-light);
            padding: 8px 12px;
            cursor: pointer;
            border-left: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.1s;
        }

        .copy-btn:hover {
            color: var(--accent-orange);
        }

        .expiry-text {
            color: var(--text-muted);
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* How to Apply Info Banner Box Component */
        .info-banner {
            background-color: var(--blue-info-bg);
            border: 1px solid var(--blue-info-border);
            border-radius: 8px;
            padding: 20px 24px;
            display: flex;
            gap: 16px;
            align-items: flex-start;
            margin-top: 15px;
        }

        .info-icon {
            color: #3498db;
            margin-top: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .info-content h3 {
            font-size: 0.9rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .info-content p {
            color: var(--text-light);
            font-size: 0.8rem;
            line-height: 1.5;
        }

        /* Utility Floating Help Button */
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
            <a href="offer.php" class="active">Offers</a>
            <a href="terms.php">Terms</a>
        </nav>
        <div class="user-badge">
            <span class="user-avatar"><?php echo htmlspecialchars(currentUserInitial()); ?></span>
            <span><?php echo htmlspecialchars(currentUserName()); ?></span>
        </div>
    </header>

    <main>
        <div class="offers-header">
            <h1>Available Offers</h1>
            <p>Exclusive discounts and promotions for our customers</p>
        </div>

        <div class="offers-grid">
            <?php foreach ($offers as $promo): ?>
                <div class="offer-card">
                    <div class="watermark-percentage"><?= $promo['percentage'] ?></div>
                    
                    <div class="offer-top-content">
                        <span class="category-badge" style="<?= $promo['badge_style'] ?>"><?= $promo['badge'] ?></span>
                        <h2 class="offer-title"><?= $promo['title'] ?></h2>
                        <p class="offer-desc"><?= $promo['description'] ?></p>
                    </div>

                    <div class="coupon-action-row">
                        <div class="code-container">
                            <span class="code-text" id="code-<?= $promo['id'] ?>"><?= $promo['code'] ?></span>
                            <button type="button" class="copy-btn" title="Copy Code" onclick="copyToClipboard('<?= $promo['code'] ?>', this)">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                            </button>
                        </div>
                        <div class="expiry-text">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                            <?= $promo['expiry'] ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="info-banner">
            <div class="info-icon">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
            </div>
            <div class="info-content">
                <h3>How to Apply a Discount</h3>
                <p>Copy your discount code and mention it to our sales team during booking, or apply it at checkout. Only one code per transaction. Discounts cannot be combined or transferred.</p>
            </div>
        </div>
    </main>

    <div class="help-icon">?</div>

    <script>
        function copyToClipboard(text, buttonElement) {
            navigator.clipboard.writeText(text).then(() => {
                // Visual feedback snippet tracking copy actions state change safely
                const originalSVG = buttonElement.innerHTML;
                buttonElement.innerHTML = `<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#2ecc71" stroke-width="3"><path d="M20 6 9 17l-5-5"/></svg>`;
                buttonElement.style.pointerEvents = 'none';

                setTimeout(() => {
                    buttonElement.innerHTML = originalSVG;
                    buttonElement.style.pointerEvents = 'auto';
                }, 1500);
            }).catch(err => {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
</body>
</html>
