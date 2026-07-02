<?php
require_once __DIR__ . '/auth.php';
requireLogin();
// Complete Mock Database Array representing the 8 vehicles in your screenshot
$inventory = [
    [
        "id" => 1,
        "name" => "Lamborghini Huracán EVO",
        "year" => 2024,
        "type" => "Luxury",
        "fuel" => "Gasoline",
        "hp" => "640 hp",
        "price" => 283220,
        "old_price" => 290000,
        "rating" => "5.0",
        "tag" => "Exclusive",
        "tag_class" => "tag-exclusive",
        "image" => "https://images.unsplash.com/photo-1621135802920-133df287f89c?q=80&w=600&auto=format&fit=crop"
    ],
    [
        "id" => 2,
        "name" => "BMW M5 Competition",
        "year" => 2024,
        "type" => "Sedan",
        "fuel" => "Gasoline",
        "hp" => "617 hp",
        "price" => 113650,
        "old_price" => 118000,
        "rating" => "4.9",
        "tag" => "Trending",
        "tag_class" => "tag-trending",
        "image" => "https://images.unsplash.com/photo-1617814076367-b759c7d7e738?q=80&w=600&auto=format&fit=crop"
    ],
    [
        "id" => 3,
        "name" => "Toyota Land Cruiser 300",
        "year" => 2024,
        "type" => "SUV",
        "fuel" => "Diesel",
        "hp" => "304 hp",
        "price" => 83720,
        "old_price" => 87000,
        "rating" => "4.9",
        "tag" => "Top Choice",
        "tag_class" => "tag-top",
        "image" => "https://images.unsplash.com/photo-1626847037657-fd3622613ce3?q=80&w=600&auto=format&fit=crop"
    ],
    [
        "id" => 4,
        "name" => "Tesla Model S Plaid",
        "year" => 2024,
        "type" => "Electric",
        "fuel" => "Electric",
        "hp" => "1020 hp",
        "price" => 97990,
        "old_price" => null,
        "rating" => "4.8",
        "tag" => "Eco",
        "tag_class" => "tag-eco",
        "image" => "https://images.unsplash.com/photo-1614162692292-7ac56d7f7f1e?q=80&w=600&auto=format&fit=crop"
    ],
    [
        "id" => 5,
        "name" => "Ford F-150 Raptor R",
        "year" => 2024,
        "type" => "Truck",
        "fuel" => "Gasoline",
        "hp" => "700 hp",
        "price" => 78200,
        "old_price" => 82000,
        "rating" => "4.8",
        "tag" => "Best Seller",
        "tag_class" => "tag-bestseller",
        "image" => "https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?q=80&w=600&auto=format&fit=crop"
    ],
    [
        "id" => 6,
        "name" => "Mercedes GLE 53 AMG",
        "year" => 2024,
        "type" => "SUV",
        "fuel" => "Hybrid",
        "hp" => "429 hp",
        "price" => 81965,
        "old_price" => 84500,
        "rating" => "4.7",
        "tag" => "Popular",
        "tag_class" => "tag-popular",
        "image" => "https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?q=80&w=600&auto=format&fit=crop"
    ],
    [
        "id" => 7,
        "name" => "Rivian R1T Adventure",
        "year" => 2024,
        "type" => "Electric",
        "fuel" => "Electric",
        "hp" => "835 hp",
        "price" => 74900,
        "old_price" => null,
        "rating" => "4.7",
        "tag" => "New",
        "tag_class" => "tag-new",
        "image" => "https://images.unsplash.com/photo-1669838707129-236b2fb7b6f2?q=80&w=600&auto=format&fit=crop"
    ],
    [
        "id" => 8,
        "name" => "Honda Civic Type R",
        "year" => 2024,
        "type" => "Sedan",
        "fuel" => "Gasoline",
        "hp" => "315 hp",
        "price" => 39897,
        "old_price" => 43000,
        "rating" => "4.6",
        "tag" => "Value",
        "tag_class" => "tag-value",
        "image" => "https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?q=80&w=600&auto=format&fit=crop"
    ]
];

// Read filtering parameters from request parameters
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$selected_category = isset($_GET['category']) ? trim($_GET['category']) : 'All';

// Apply real-time PHP filtering
$filtered_inventory = array_filter($inventory, function($car) use ($search_query, $selected_category) {
    $matches_search = empty($search_query) || (stripos($car['name'], $search_query) !== false) || (stripos($car['type'], $search_query) !== false);
    $matches_category = ($selected_category === 'All') || (strcasecmp($car['type'], $selected_category) === 0);
    return $matches_search && $matches_category;
});

function formatPrice($num) {
    return '$' . number_format($num);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Hub - Full Inventory</title>
    <style>
        :root {
            --bg-dark: #070a12;
            --panel-dark: #0f1422;
            --card-bg: #131929;
            --accent-orange: #ff9f05;
            --text-muted: #6c757d;
            --text-light: #a0a5b5;
            --border-color: #1f283e;
            --input-bg: #111625;
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

        /* Inventory Layout Structure */
        main {
            padding: 40px 6%;
        }

        .inventory-header h1 {
            font-size: 2rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .inventory-header p {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-top: 4px;
            margin-bottom: 30px;
        }

        /* Filter controls layout */
        .controls-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 25px;
        }

        .search-container {
            position: relative;
            flex: 1;
            max-width: 600px;
        }

        .search-container input {
            width: 100%;
            background-color: var(--input-bg);
            border: 1px solid var(--border-color);
            padding: 12px 16px 12px 42px;
            border-radius: 8px;
            color: #fff;
            font-size: 0.9rem;
            outline: none;
        }

        .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }

        .sort-select {
            background-color: var(--input-bg);
            border: 1px solid var(--border-color);
            color: #fff;
            padding: 0 16px;
            border-radius: 8px;
            font-size: 0.9rem;
            outline: none;
            cursor: pointer;
        }

        /* Category System Pills */
        .categories-row {
            display: flex;
            gap: 10px;
            margin-bottom: 35px;
            flex-wrap: wrap;
        }

        .pill {
            background-color: #121826;
            border: 1px solid var(--border-color);
            color: var(--text-light);
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .pill.active, .pill:hover {
            background-color: var(--accent-orange);
            color: #000;
            border-color: var(--accent-orange);
        }

        /* Grid View Layout */
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
        }

        /* Cards Elements */
        .card {
            background-color: var(--card-bg);
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.02);
            position: relative;
        }

        .card-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .card-body {
            padding: 20px;
        }

        .card-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .card-title {
            font-size: 1.05rem;
            font-weight: 700;
        }

        .card-year {
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        .specs {
            display: flex;
            gap: 10px;
            color: var(--text-muted);
            font-size: 0.75rem;
            margin-bottom: 20px;
        }

        .card-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid rgba(255, 255, 255, 0.04);
            padding-top: 15px;
        }

        .old-price {
            color: var(--text-muted);
            text-decoration: line-through;
            font-size: 0.75rem;
            display: block;
        }

        .price {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--accent-orange);
        }

        .monthly-estimate {
            font-size: 0.7rem;
            color: var(--text-muted);
            font-weight: normal;
        }

        /* Dynamic Badging Tokens matching image tags colors */
        .tag {
            position: absolute;
            top: 12px;
            left: 12px;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .tag-exclusive { background-color: rgba(220, 53, 69, 0.2); color: #ff6b6b; border: 1px solid rgba(220, 53, 69, 0.4); }
        .tag-trending { background-color: rgba(255, 159, 5, 0.2); color: var(--accent-orange); border: 1px solid rgba(255, 159, 5, 0.4); }
        .tag-top { background-color: rgba(13, 110, 253, 0.2); color: #6ea8fe; border: 1px solid rgba(13, 110, 253, 0.4); }
        .tag-eco { background-color: rgba(25, 135, 84, 0.2); color: #75b798; border: 1px solid rgba(25, 135, 84, 0.4); }
        .tag-bestseller { background-color: rgba(111, 66, 193, 0.2); color: #a370f7; border: 1px solid rgba(111, 66, 193, 0.4); }
        .tag-popular { background-color: rgba(23, 162, 184, 0.2); color: #6edff6; border: 1px solid rgba(23, 162, 184, 0.4); }
        .tag-new { background-color: rgba(255, 255, 255, 0.1); color: #fff; border: 1px solid rgba(255, 255, 255, 0.2); }
        .tag-value { background-color: rgba(224, 86, 36, 0.2); color: #f07444; border: 1px solid rgba(224, 86, 36, 0.4); }

        .rating {
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

        .rating span { color: var(--accent-orange); }

        .circle-btn {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #1a233a;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 0.85rem;
        }

        .no-results {
            grid-column: 1 / -1;
            text-align: center;
            padding: 60px;
            color: var(--text-muted);
        }

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
            <a href="Inventory.php" class="active">Inventory</a>
            <a href="Finance.php">Finance</a>
            <a href="offer.php">Offers</a>
            <a href="terms.php">Terms</a>
        </nav>
        <div class="user-badge">
            <span class="user-avatar"><?php echo htmlspecialchars(currentUserInitial()); ?></span>
            <span><?php echo htmlspecialchars(currentUserName()); ?></span>
        </div>
    </header>

    <main>
        <div class="inventory-header">
            <h1>Full Inventory</h1>
            <p><?php echo count($filtered_inventory); ?> vehicles available</p>
        </div>

        <form method="GET" action="" id="searchForm">
            <input type="hidden" name="category" id="categoryInput" value="<?php echo htmlspecialchars($selected_category); ?>">
            
            <div class="controls-row">
                <div class="search-container">
                    <span class="search-icon">🔍</span>
                    <input type="text" name="search" placeholder="Search by name, type, or fuel..." value="<?php echo htmlspecialchars($search_query); ?>" oninput="debounceSubmit()">
                </div>
                <select class="sort-select" name="sort">
                    <option>Top Rated</option>
                    <option>Price: Low to High</option>
                    <option>Price: High to Low</option>
                </select>
            </div>
        </form>

        <div class="categories-row">
            <?php 
            $categories = ['All', 'Sedan', 'SUV', 'Truck', 'Electric', 'Luxury'];
            foreach ($categories as $cat): 
                $active_class = ($selected_category === $cat) ? 'active' : '';
                // Generate URL with current search query preserved
                $url = "?" . http_build_query(['search' => $search_query, 'category' => $cat]);
            ?>
                <a href="<?php echo $url; ?>" class="pill <?php echo $active_class; ?>"><?php echo $cat; ?></a>
            <?php endforeach; ?>
        </div>

        <div class="grid">
            <?php if (!empty($filtered_inventory)): ?>
                <?php foreach ($filtered_inventory as $car): ?>
                    <div class="card">
                        <span class="tag <?php echo $car['tag_class']; ?>"><?php echo $car['tag']; ?></span>
                        <div class="rating"><span>★</span> <?php echo $car['rating']; ?></div>
                        
                        <img src="<?php echo $car['image']; ?>" alt="<?php echo $car['name']; ?>" class="card-img">
                        
                        <div class="card-body">
                            <div class="card-top">
                                <h3 class="card-title"><?php echo $car['name']; ?></h3>
                                <span class="card-year"><?php echo $car['year']; ?></span>
                            </div>
                            
                            <div class="specs">
                                <span><?php echo $car['type']; ?></span> &bull; 
                                <span><?php echo $car['fuel']; ?></span> &bull; 
                                <span><?php echo $car['hp']; ?></span>
                            </div>
                            
                            <div class="card-bottom">
                                <div>
                                    <?php if ($car['old_price']): ?>
                                        <span class="old-price"><?php echo formatPrice($car['old_price']); ?></span>
                                    <?php endif; ?>
                                    <span class="price"><?php echo formatPrice($car['price']); ?></span>
                                    <span class="monthly-estimate">/ <?php echo formatPrice(round($car['price'] / 64)); ?> mo est.</span>
                                </div>
                                <a href="Finance.php?vehicle_price=<?php echo urlencode($car['price']); ?>" class="circle-btn">&rsaquo;</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-results">
                    <h3>No vehicles match your search criteria.</h3>
                    <p>Try resetting the category filter or changing your search phrase.</p>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <div class="help-icon">?</div>

    <script>
        // Automatic filtering submit logic with debounce
        let timer;
        function debounceSubmit() {
            clearTimeout(timer);
            timer = setTimeout(() => {
                document.getElementById('searchForm').submit();
            }, 500); // Submits form 500ms after you stop typing
        }
    </script>
</body>
</html>
