<?php
require_once __DIR__ . '/auth.php';
requireLogin();
handleLogout();

// Mock database array for functional dynamic rendering
$featured_vehicles = [
    [
        "id" => 1,
        "name" => "BMW M5 Competition",
        "year" => 2024,
        "type" => "Sedan",
        "fuel" => "Gasoline",
        "hp" => "617 hp",
        "price" => 113650,
        "rating" => "4.9",
        "image" => "https://images.unsplash.com/photo-1617814076367-b759c7d7e738?q=80&w=600&auto=format&fit=crop"
    ],
    [
        "id" => 2,
        "name" => "Toyota Land Cruiser 300",
        "year" => 2024,
        "type" => "SUV",
        "fuel" => "Diesel",
        "hp" => "304 hp",
        "price" => 83720,
        "rating" => "4.8",
        "image" => "https://images.unsplash.com/photo-1626847037657-fd3622613ce3?q=80&w=600&auto=format&fit=crop"
    ],
    [
        "id" => 3,
        "name" => "Tesla Model S Plaid",
        "year" => 2024,
        "type" => "Electric",
        "fuel" => "Electric",
        "hp" => "1020 hp",
        "price" => 97990,
        "rating" => "4.9",
        "image" => "https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?q=80&w=600&auto=format&fit=crop"
    ],
    [
        "id" => 4,
        "name" => "Mercedes GLE 53 AMG",
        "year" => 2024,
        "type" => "SUV",
        "fuel" => "Hybrid",
        "hp" => "429 hp",
        "price" => 81965,
        "rating" => "4.7",
        "image" => "https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?q=80&w=600&auto=format&fit=crop"
    ]
];

// Simple format helper for prices
function formatPrice($num) {
    return '$' . number_format($num);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Hub - Find Your Perfect Drive</title>
    <style>
        :root {
            --bg-dark: #070a12;
            --panel-dark: #0f1422;
            --card-bg: #131929;
            --accent-orange: #ff9f05;
            --text-muted: #6c757d;
            --text-light: #a0a5b5;
            --border-color: #1f283e;
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
            overflow-x: hidden;
        }

        /* Header Navigation */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 6%;
            background-color: rgba(7, 10, 18, 0.8);
            backdrop-filter: blur(10px);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 100;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
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
            transition: color 0.2s;
        }

        nav a.active, nav a:hover {
            color: var(--accent-orange);
        }

        .user-menu {
            position: relative;
            cursor: pointer;
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

        /* Dropdown styling */
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 40px;
            background-color: var(--panel-dark);
            min-width: 150px;
            border-radius: 8px;
            border: 1px solid var(--border-color);
            overflow: hidden;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.5);
        }

        .dropdown-content a {
            color: white;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-size: 0.85rem;
        }

        .dropdown-content a:hover {
            background-color: #1c253d;
        }

        /* Hero Wrapper */
        .hero {
            position: relative;
            min-height: 85vh;
            display: flex;
            align-items: center;
            padding: 100px 6% 40px 6%;
            background: linear-gradient(90deg, #070a12 35%, rgba(7, 10, 18, 0.3) 60%, rgba(7, 10, 18, 0) 100%),
                        url('https://images.unsplash.com/photo-1617788138017-80ad40651399?q=80&w=1800&auto=format&fit=crop') center right / cover no-repeat;
        }

        .hero-content {
            max-width: 550px;
            z-index: 2;
        }

        .tagline {
            color: var(--accent-orange);
            text-transform: uppercase;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tagline::before {
            content: '';
            display: inline-block;
            width: 16px;
            height: 2px;
            background-color: var(--accent-orange);
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 900;
            line-height: 1.1;
            text-transform: uppercase;
            margin-bottom: 20px;
            letter-spacing: -1px;
        }

        .hero h1 span {
            color: var(--accent-orange);
        }

        .hero p {
            color: var(--text-light);
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 30px;
        }

        .cta-group {
            display: flex;
            gap: 15px;
        }

        .btn-primary {
            background-color: var(--accent-orange);
            color: #000;
            padding: 14px 28px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.9rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .btn-secondary {
            border: 1px solid var(--border-color);
            background: transparent;
            color: #fff;
            padding: 14px 28px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
        }

        /* Stats Bar Row */
        .stats-bar {
            background-color: #0a0f1d;
            border-top: 1px solid rgba(255, 255, 255, 0.03);
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            display: grid;
            grid-template-columns: repeat(4, 150px);
            justify-content: center;
            gap: 80px;
            padding: 30px 0;
            text-align: center;
        }

        .stat-item h3 {
            color: var(--accent-orange);
            font-size: 1.4rem;
            font-weight: 700;
        }

        .stat-item p {
            color: var(--text-muted);
            font-size: 0.75rem;
            margin-top: 4px;
        }

        /* Showcase Grid Container */
        .showcase {
            padding: 60px 6%;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: end;
            margin-bottom: 30px;
        }

        .section-header h2 {
            font-size: 1.5rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 800;
        }

        .section-header .subtext {
            color: var(--text-muted);
            font-size: 0.8rem;
            margin-top: 4px;
        }

        .view-all {
            color: var(--accent-orange);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 24px;
            margin-bottom: 50px;
        }

        /* Vehicle Cards Component */
        .card {
            background-color: var(--card-bg);
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.02);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .card-img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            background-color: #1a2238;
        }

        .card-body {
            padding: 20px;
        }

        .card-top-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .card-title {
            font-size: 1.05rem;
            font-weight: 700;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .card-year {
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        .specs-row {
            display: flex;
            gap: 12px;
            color: var(--text-muted);
            font-size: 0.75rem;
            margin-bottom: 20px;
        }

        .card-bottom-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid rgba(255, 255, 255, 0.04);
            padding-top: 15px;
        }

        .card-price {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--accent-orange);
        }

        .card-mo-price {
            font-size: 0.7rem;
            color: var(--text-muted);
            display: block;
            font-weight: normal;
            margin-top: 2px;
        }

        .rating-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background-color: rgba(7, 10, 18, 0.7);
            backdrop-filter: blur(4px);
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .rating-badge span {
            color: var(--accent-orange);
        }

        .action-circle-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #1a233a;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 0.8rem;
            transition: background-color 0.2s;
        }

        .action-circle-btn:hover {
            background-color: var(--accent-orange);
            color: #000;
        }

        /* Banner Container CTA */
        .finance-banner {
            background-color: var(--panel-dark);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 30px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .banner-text h4 {
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .banner-text p {
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        .btn-banner {
            background-color: var(--accent-orange);
            color: #000;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        /* Responsiveness view changes */
        @media(max-width: 900px) {
            nav { display: none; }
            .hero { background-position: 75% center; text-align: center; justify-content: center; padding-top: 140px; }
            .hero h1 { font-size: 2.5rem; }
            .cta-group { justify-content: center; }
            .stats-bar { grid-template-columns: repeat(2, 1fr); gap: 20px; padding: 20px; }
            .finance-banner { flex-direction: column; text-align: center; gap: 20px; }
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
            <a href="home.php" class="active">Home</a>
            <a href="Inventory.php">Inventory</a>
            <a href="Finance.php">Finance</a>
            <a href="offer.php">Offers</a>
            <a href="terms.php">Terms</a>
        </nav>

        <div class="user-menu" onclick="toggleDropdown()">
            <div class="user-badge">
                <span class="user-avatar"><?php echo htmlspecialchars(currentUserInitial()); ?></span>
            <span><?php echo htmlspecialchars(currentUserName()); ?></span>
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
            </div>
            <div class="dropdown-content" id="userDropdown">
                <a href="home.php">My Profile</a>
                <a href="Finance.php">Bookings</a>
                <a href="home.php?logout=1">Sign Out</a>
            </div>
        </div>
    </header>

    <section class="hero">
        <div class="hero-content">
            <div class="tagline">Premium Automotive</div>
            <h1>Find Your<br><span>Perfect</span><br>Drive.</h1>
            <p>Explore 500+ premium vehicles with flexible financing, exclusive deals, and seamless booking configuration built directly around your lifecycle.</p>
            <div class="cta-group">
                <a href="Inventory.php" class="btn-primary">Browse Inventory <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path d="m9 18 6-6-6-6"/></svg></a>
                <a href="Finance.php" class="btn-secondary">Finance Calculator</a>
            </div>
        </div>
    </section>

    <section class="stats-bar">
        <div class="stat-item">
            <h3>500+</h3>
            <p>Vehicles in Stock</p>
        </div>
        <div class="stat-item">
            <h3>4.9★</h3>
            <p>Customer Rating</p>
        </div>
        <div class="stat-item">
            <h3>15yr</h3>
            <p>In Business</p>
        </div>
        <div class="stat-item">
            <h3>98%</h3>
            <p>Satisfaction Rate</p>
        </div>
    </section>

    <section class="showcase">
        <div class="section-header">
            <div>
                <h2>Featured Vehicles</h2>
                <div class="subtext">Handpicked from our premium inventory</div>
            </div>
            <a href="Inventory.php" class="view-all">View All &rsaquo;</a>
        </div>

        <div class="grid">
            <?php foreach ($featured_vehicles as $car): ?>
                <div class="card">
                    <div class="rating-badge">
                        <span>★</span> <?php echo $car['rating']; ?>
                    </div>
                    <img src="<?php echo $car['image']; ?>" class="card-img" alt="<?php echo $car['name']; ?>">
                    <div class="card-body">
                        <div class="card-top-row">
                            <h3 class="card-title"><?php echo $car['name']; ?></h3>
                            <span class="card-year"><?php echo $car['year']; ?></span>
                        </div>
                        <div class="specs-row">
                            <span><?php echo $car['type']; ?></span> &bull; 
                            <span><?php echo $car['fuel']; ?></span> &bull; 
                            <span><?php echo $car['hp']; ?></span>
                        </div>
                        <div class="card-bottom-row">
                            <div class="card-price">
                                <?php echo formatPrice($car['price']); ?>
                                <span class="card-mo-price"><?php echo formatPrice(round($car['price'] / 60)); ?>/mo est</span>
                            </div>
                            <a href="Finance.php?vehicle_price=<?php echo urlencode($car['price']); ?>" class="action-circle-btn">&rsaquo;</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="finance-banner">
            <div class="banner-text">
                <h4>Ready to finance your next vehicle?</h4>
                <p>Rates from 3.9% APR. Get your payment estimates in seconds.</p>
            </div>
            <a href="Finance.php" class="btn-banner" style="text-decoration:none; display:inline-flex; align-items:center; gap:8px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 11h10M7 15h10M13 7v4M17 15V7"/></svg>
                Calculate Now
            </a>
        </div>
    </section>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.closest('.user-menu')) {
                document.getElementById('userDropdown').style.display = 'none';
            }
        }
    </script>
</body>
</html>
