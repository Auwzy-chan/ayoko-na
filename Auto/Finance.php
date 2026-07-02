<?php
require_once __DIR__ . '/auth.php';
requireLogin();

// Default values matching your screenshot setup
$vehiclePrice = isset($_POST['vehicle_price']) ? floatval($_POST['vehicle_price']) : (isset($_GET['vehicle_price']) ? floatval($_GET['vehicle_price']) : 75000);
$downPayment = isset($_POST['down_payment']) ? floatval($_POST['down_payment']) : 15000;
$interestRate = isset($_POST['interest_rate']) ? floatval($_POST['interest_rate']) : 4.5;
$loanTerm = isset($_POST['loan_term']) ? intval($_POST['loan_term']) : 60;

// Serverside math calculations
$loanAmount = $vehiclePrice - $downPayment;
if ($loanAmount < 0) $loanAmount = 0;

$monthlyRate = ($interestRate / 100) / 12;

if ($monthlyRate > 0) {
    $monthlyPayment = $loanAmount * ($monthlyRate * pow(1 + $monthlyRate, $loanTerm)) / (pow(1 + $monthlyRate, $loanTerm) - 1);
} else {
    $monthlyPayment = $loanTerm > 0 ? $loanAmount / $loanTerm : 0;
}

$totalCost = ($monthlyPayment * $loanTerm) + $downPayment;
$totalInterest = $totalCost - $vehiclePrice;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Hub - Financing Calculator</title>
    <style>
        :root {
            --bg-dark: #070a12;
            --panel-dark: #0f1422;
            --card-bg: #131929;
            --accent-orange: #ff9f05;
            --text-muted: #6c757d;
            --text-light: #a0a5b5;
            --border-color: #1f283e;
            --slider-track: #1b2236;
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

        /* Calculator Content Structure */
        main {
            padding: 40px 6%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .calc-header h1 {
            font-size: 2rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .calc-header p {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin-top: 4px;
            margin-bottom: 40px;
        }

        .calculator-container {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 40px;
            align-items: start;
        }

        /* Sliders Box Layout */
        .slider-box {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 24px;
            border: 1px solid rgba(255, 255, 255, 0.02);
            margin-bottom: 20px;
        }

        .slider-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .slider-label {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .slider-value {
            font-size: 1.2rem;
            font-weight: 700;
        }

        .range-wrapper {
            position: relative;
        }

        input[type="range"] {
            -webkit-appearance: none;
            width: 100%;
            height: 6px;
            border-radius: 3px;
            background: var(--slider-track);
            outline: none;
        }

        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--accent-orange);
            cursor: pointer;
            transition: transform 0.1s;
        }

        input[type="range"]::-webkit-slider-thumb:hover {
            transform: scale(1.2);
        }

        .range-limits {
            display: flex;
            justify-content: space-between;
            color: #38425a;
            font-size: 0.75rem;
            margin-top: 8px;
        }

        /* Loan Term Radio Segment controls */
        .term-box {
            background-color: var(--card-bg);
            border-radius: 12px;
            padding: 24px;
            border: 1px solid rgba(255, 255, 255, 0.02);
        }

        .term-box p {
            color: var(--text-light);
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .term-group {
            display: flex;
            gap: 12px;
        }

        .term-btn {
            flex: 1;
            position: relative;
        }

        .term-btn input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 2;
        }

        .term-label {
            display: block;
            text-align: center;
            background-color: #111625;
            border: 1px solid var(--border-color);
            color: var(--text-light);
            padding: 14px 0;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .term-btn input[type="radio"]:checked + .term-label {
            background-color: var(--accent-orange);
            color: #000;
            border-color: var(--accent-orange);
        }

        /* Summary Side Block Panel */
        .summary-panel {
            background-color: var(--panel-dark);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 35px;
        }

        .summary-title {
            color: var(--text-muted);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 25px;
            font-weight: 700;
        }

        .payment-display {
            text-align: center;
            margin-bottom: 35px;
        }

        .payment-display p {
            color: var(--text-muted);
            font-size: 0.8rem;
            margin-bottom: 5px;
        }

        .main-payment-amount {
            color: var(--accent-orange);
            font-size: 3rem;
            font-weight: 900;
            line-height: 1;
        }

        .payment-period {
            color: var(--text-muted);
            font-size: 0.75rem;
            margin-top: 6px;
        }

        .breakdown-row {
            display: flex;
            justify-content: space-between;
            padding: 14px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.03);
            font-size: 0.9rem;
        }

        .breakdown-row:last-of-type {
            border-bottom: none;
            padding-bottom: 0;
        }

        .breakdown-label {
            color: var(--text-light);
        }

        .breakdown-val {
            font-weight: 700;
        }

        .breakdown-val.highlight-green { color: #2ecc71; }
        .breakdown-val.highlight-orange { color: #e67e22; }
        .breakdown-val.highlight-yellow { color: var(--accent-orange); }

        .disclaimer {
            color: #3c4865;
            font-size: 0.7rem;
            text-align: center;
            margin-top: 30px;
            line-height: 1.4;
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

        @media (max-width: 900px) {
            .calculator-container { grid-template-columns: 1fr; }
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
            <a href="Finance.php" class="active">Finance</a>
            <a href="offer.php">Offers</a>
            <a href="terms.php">Terms</a>
        </nav>
        <div class="user-badge">
            <span class="user-avatar"><?php echo htmlspecialchars(currentUserInitial()); ?></span>
            <span><?php echo htmlspecialchars(currentUserName()); ?></span>
        </div>
    </header>

    <main>
        <div class="calc-header">
            <h1>Financing Calculator</h1>
            <p>Estimate monthly payments and total cost of ownership</p>
        </div>

        <form method="POST" action="" id="calcForm">
            <div class="calculator-container">
                
                <div class="inputs-column">
                    
                    <div class="slider-box">
                        <div class="slider-header">
                            <span class="slider-label">Vehicle Price</span>
                            <span class="slider-value" id="val-price">$<?= number_format($vehiclePrice) ?></span>
                        </div>
                        <div class="range-wrapper">
                            <input type="range" name="vehicle_price" id="input-price" min="10000" max="300000" step="1000" value="<?= $vehiclePrice ?>" oninput="calculatePayments()">
                            <div class="range-limits">
                                <span>$10,000</span>
                                <span>$300,000</span>
                            </div>
                        </div>
                    </div>

                    <div class="slider-box">
                        <div class="slider-header">
                            <span class="slider-label">Down Payment</span>
                            <span class="slider-value" id="val-down">$<?= number_format($downPayment) ?></span>
                        </div>
                        <div class="range-wrapper">
                            <input type="range" name="down_payment" id="input-down" min="0" max="150000" step="500" value="<?= $downPayment ?>" oninput="calculatePayments()">
                            <div class="range-limits">
                                <span>$0</span>
                                <span>$150000</span>
                            </div>
                        </div>
                    </div>

                    <div class="slider-box">
                        <div class="slider-header">
                            <span class="slider-label">Interest Rate (APR)</span>
                            <span class="slider-value" id="val-rate"><?= number_format($interestRate, 1) ?>%</span>
                        </div>
                        <div class="range-wrapper">
                            <input type="range" name="interest_rate" id="input-rate" min="1.0" max="15.0" step="0.1" value="<?= $interestRate ?>" oninput="calculatePayments()">
                            <div class="range-limits">
                                <span>1.0%</span>
                                <span>15.0%</span>
                            </div>
                        </div>
                    </div>

                    <div class="term-box">
                        <p>Loan Term</p>
                        <div class="term-group">
                            <?php foreach ([24, 36, 48, 60, 72] as $term): ?>
                                <div class="term-btn">
                                    <input type="radio" name="loan_term" value="<?= $term ?>" <?= $term == $loanTerm ? 'checked' : '' ?> onchange="calculatePayments()">
                                    <span class="term-label"><?= $term ?>mo</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                </div>

                <div class="summary-panel">
                    <div class="summary-title">Payment Summary</div>
                    
                    <div class="payment-display">
                        <p>Monthly Payment</p>
                        <div class="main-payment-amount" id="display-monthly">$<?= number_format($monthlyPayment) ?></div>
                        <div class="payment-period" id="display-period">per month for <?= $loanTerm ?> months</div>
                    </div>

                    <div class="breakdown-row">
                        <span class="breakdown-label">Vehicle Price</span>
                        <span class="breakdown-val" id="lbl-price">$<?= number_format($vehiclePrice) ?></span>
                    </div>

                    <div class="breakdown-row">
                        <span class="breakdown-label">Down Payment</span>
                        <span class="breakdown-val highlight-green" id="lbl-down">$<?= number_format($downPayment) ?></span>
                    </div>

                    <div class="breakdown-row">
                        <span class="breakdown-label">Loan Amount</span>
                        <span class="breakdown-val" id="lbl-loan">$<?= number_format($loanAmount) ?></span>
                    </div>

                    <div class="breakdown-row">
                        <span class="breakdown-label">Total Interest</span>
                        <span class="breakdown-val highlight-orange" id="lbl-interest">$<?= number_format($totalInterest) ?></span>
                    </div>

                    <div class="breakdown-row">
                        <span class="breakdown-label">Total Cost</span>
                        <span class="breakdown-val highlight-yellow" id="lbl-total">$<?= number_format($totalCost) ?></span>
                    </div>

                    <div class="disclaimer">
                        Estimates only. Actual rates depend on credit score and lender terms.
                    </div>
                </div>

            </div>
        </form>
    </main>

    <div class="help-icon">?</div>

    <script>
        // High-fidelity Javascript calculations keeping layout perfectly responsive instantly
        function calculatePayments() {
            const price = parseFloat(document.getElementById('input-price').value);
            const down = parseFloat(document.getElementById('input-down').value);
            const rate = parseFloat(document.getElementById('input-rate').value);
            const term = parseInt(document.querySelector('input[name="loan_term"]:checked').value);

            // Dynamically update UI text values over sliders
            document.getElementById('val-price').textContent = '$' + price.toLocaleString();
            document.getElementById('val-down').textContent = '$' + down.toLocaleString();
            document.getElementById('val-rate').textContent = rate.toFixed(1) + '%';
            
            // Financial logic structure
            let loanAmount = price - down;
            if (loanAmount < 0) loanAmount = 0;

            const monthlyRate = (rate / 100) / 12;
            let monthlyPayment = 0;

            if (monthlyRate > 0) {
                monthlyPayment = loanAmount * (monthlyRate * Math.pow(1 + monthlyRate, term)) / (Math.pow(1 + monthlyRate, term) - 1);
            } else {
                monthlyPayment = term > 0 ? loanAmount / term : 0;
            }

            if(isNaN(monthlyPayment) || !isFinite(monthlyPayment)) monthlyPayment = 0;

            const totalCost = (monthlyPayment * term) + down;
            const totalInterest = totalCost - price;

            // Display components update pipeline
            document.getElementById('display-monthly').textContent = '$' + Math.round(monthlyPayment).toLocaleString();
            document.getElementById('display-period').textContent = `per month for ${term} months`;
            
            document.getElementById('lbl-price').textContent = '$' + price.toLocaleString();
            document.getElementById('lbl-down').textContent = '$' + down.toLocaleString();
            document.getElementById('lbl-loan').textContent = '$' + Math.round(loanAmount).toLocaleString();
            document.getElementById('lbl-interest').textContent = '$' + Math.round(Math.max(0, totalInterest)).toLocaleString();
            document.getElementById('lbl-total').textContent = '$' + Math.round(totalCost).toLocaleString();
        }
    </script>
</body>
</html>
