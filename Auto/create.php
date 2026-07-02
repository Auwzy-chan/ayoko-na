<?php
require_once __DIR__ . '/auth.php';

// Initialize message variables
$message = "";
$messageType = "";
$dbReady = true;

try {
    ensureUsersTable();
} catch (mysqli_sql_exception $e) {
    $dbReady = false;
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? 'signin';
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'] ?? '';
    
    // Optional/conditional registration fields
    $fullname = trim($_POST['fullname'] ?? '');
    $phone = trim($_POST['phone'] ?? '');

    // Common Validation
    if (!$email) {
        $message = "Please enter a valid email address.";
        $messageType = "error";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters long.";
        $messageType = "error";
    } elseif (!$dbReady) {
        $message = "Database connection failed. Please check db.php and make sure MySQL is running.";
        $messageType = "error";
    } else {
        // State-specific processing
        if ($action === 'create') {
            if (empty($fullname)) {
                $message = "Please enter your full name.";
                $messageType = "error";
            } elseif (empty($phone)) {
                $message = "Please enter your phone number.";
                $messageType = "error";
            } else {
                try {
                    $db = getDbConnection();
                    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
                    $stmt->bind_param("s", $email);
                    $stmt->execute();

                    if ($stmt->get_result()->num_rows > 0) {
                        $message = "An account with that email already exists. Please sign in instead.";
                        $messageType = "error";
                    } else {
                        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                        $stmt = $db->prepare("INSERT INTO users (fullname, email, phone, password_hash) VALUES (?, ?, ?, ?)");
                        $stmt->bind_param("ssss", $fullname, $email, $phone, $passwordHash);
                        $stmt->execute();

                        signInUser([
                            'id' => $stmt->insert_id,
                            'fullname' => $fullname,
                            'email' => $email
                        ]);
                        header('Location: home.php');
                        exit;
                    }
                } catch (mysqli_sql_exception $e) {
                    $message = "Database connection failed. Please check db.php and make sure MySQL is running.";
                    $messageType = "error";
                }
            }
        } else {
            try {
                $db = getDbConnection();
                $stmt = $db->prepare("SELECT id, fullname, email, password_hash FROM users WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $user = $stmt->get_result()->fetch_assoc();

                if ($user && password_verify($password, $user['password_hash'])) {
                    signInUser($user);
                    header('Location: home.php');
                    exit;
                }

                $message = "Invalid email or password.";
                $messageType = "error";
            } catch (mysqli_sql_exception $e) {
                $message = "Database connection failed. Please check db.php and make sure MySQL is running.";
                $messageType = "error";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Hub - Join Us</title>
    <style>
        :root {
            --bg-dark: #0a0e17;
            --panel-dark: #0f1422;
            --accent-orange: #ff9f05;
            --text-muted: #6c757d;
            --input-bg: #151b2c;
            --input-border: #202b44;
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
            min-height: 100vh;
            display: flex;
        }

        .container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* Left Hero Panel */
        .hero-panel {
            flex: 1;
            background: linear-gradient(rgba(10, 14, 23, 0.6), rgba(10, 14, 23, 0.85)), 
                        url('https://images.unsplash.com/photo-1617788138017-80ad40651399?q=80&w=1200&auto=format&fit=crop') center center / cover no-repeat;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-icon {
            background-color: var(--accent-orange);
            color: #000;
            padding: 8px;
            border-radius: 8px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-name {
            font-size: 1.2rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .hero-text-container {
            max-width: 480px;
            margin-bottom: 40px;
        }

        .tagline {
            color: var(--accent-orange);
            text-transform: uppercase;
            font-size: 0.8rem;
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
            width: 20px;
            height: 2px;
            background-color: var(--accent-orange);
        }

        .hero-title {
            font-size: 2.8rem;
            font-weight: 900;
            line-height: 1.1;
            text-transform: uppercase;
            margin-bottom: 20px;
        }

        .hero-title span {
            color: var(--accent-orange);
        }

        .hero-desc {
            color: #a0a5b5;
            font-size: 0.95rem;
            line-height: 1.5;
        }

        .stats {
            display: flex;
            gap: 40px;
        }

        .stat-item h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent-orange);
        }

        .stat-item p {
            color: var(--text-muted);
            font-size: 0.8rem;
            margin-top: 4px;
        }

        /* Right Form Panel */
        .form-panel {
            width: 50%;
            background-color: var(--panel-dark);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 60px;
            position: relative;
        }

        .form-container {
            width: 100%;
            max-width: 420px;
        }

        .form-header h2 {
            font-size: 2rem;
            font-weight: 800;
            text-transform: uppercase;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .form-header p {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 25px;
        }

        /* Segmented Toggle Control */
        .toggle-container {
            display: flex;
            background-color: var(--input-bg);
            border: 1px solid var(--input-border);
            padding: 4px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .toggle-btn {
            flex: 1;
            background: transparent;
            border: none;
            color: var(--text-muted);
            padding: 12px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            border-radius: 6px;
            transition: all 0.2s ease;
        }

        .toggle-btn.active {
            background-color: var(--accent-orange);
            color: #000000;
        }

        /* Form Inputs */
        .form-group {
            margin-bottom: 18px;
        }

        .form-group.conditional-field {
            display: none; /* Controlled via JS toggle */
        }

        .form-group label {
            display: block;
            color: #a0a5b5;
            font-size: 0.85rem;
            margin-bottom: 8px;
        }

        .form-group input {
            width: 100%;
            background-color: var(--input-bg);
            border: 1px solid var(--input-border);
            padding: 14px;
            border-radius: 8px;
            color: #ffffff;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-group input::placeholder {
            color: #3b465e;
        }

        .form-group input:focus {
            border-color: var(--accent-orange);
        }

        /* Feedback Alerts */
        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        .alert-error {
            background-color: #842029;
            color: #f8d7da;
            border: 1px solid #f5c2c7;
        }
        .alert-success {
            background-color: #0f5132;
            color: #d1e7dd;
            border: 1px solid #badbcc;
        }

        /* Main Action Button */
        .submit-btn {
            width: 100%;
            background-color: var(--accent-orange);
            color: #000000;
            border: none;
            padding: 14px;
            font-size: 1rem;
            font-weight: 700;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 10px;
            transition: opacity 0.2s;
        }

        .submit-btn:hover {
            opacity: 0.9;
        }

        .help-icon {
            position: absolute;
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
            cursor: pointer;
        }

        /* Responsive Breakpoint */
        @media (max-width: 900px) {
            body { display: block; }
            .container { flex-direction: column; }
            .hero-panel { min-height: 400px; }
            .form-panel { width: 100%; padding: 40px 20px; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="hero-panel">
        <div class="brand">
            <div class="brand-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9A3.7 3.7 0 0 0 2 12v4c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg>
            </div>
            <div class="brand-name">Auto Hub</div>
        </div>

        <div class="hero-text-container">
            <div class="tagline">Premier Dealership</div>
            <h1 class="hero-title">Your Next<br><span>Dream Car</span><br>Awaits.</h1>
            <p class="hero-desc">Browse, finance, and book your next premium vehicle &mdash; all in one place.</p>
        </div>

        <div class="stats">
            <div class="stat-item">
                <h3>500+</h3>
                <p>Vehicles</p>
            </div>
            <div class="stat-item">
                <h3>4.9★</h3>
                <p>Rating</p>
            </div>
            <div class="stat-item">
                <h3>15yr</h3>
                <p>Experience</p>
            </div>
        </div>
    </div>

    <div class="form-panel">
        <div class="form-container">
            <div class="form-header">
                <h2 id="form-title">Join Auto Hub</h2>
                <p id="form-desc">Create your account to get started</p>
            </div>

            <?php if (!empty($message)): ?>
                <div class="alert alert-<?php echo $messageType; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <div class="toggle-container">
                <button type="button" class="toggle-btn" id="btn-signin" onclick="setFormAction('signin')">Sign In</button>
                <button type="button" class="toggle-btn active" id="btn-create" onclick="setFormAction('create')">Create Account</button>
            </div>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input type="hidden" name="action" id="action-input" value="create">

                <div class="form-group conditional-field" id="field-fullname">
                    <label for="fullname">Full Name</label>
                    <input type="text" id="fullname" name="fullname" placeholder="Alex Rivera" value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" placeholder="you@example.com" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>

                <div class="form-group conditional-field" id="field-phone">
                    <label for="phone">Phone Number</label>
                    <input type="text" id="phone" name="phone" placeholder="+1 (555) 000-0000" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" required>
                </div>

                <button type="submit" class="submit-btn" id="submit-btn-text">Create Account</button>
            </form>
        </div>
        <div class="help-icon">?</div>
    </div>
</div>

<script>
    function setFormAction(action) {
        const actionInput = document.getElementById('action-input');
        const btnSignIn = document.getElementById('btn-signin');
        const btnCreate = document.getElementById('btn-create');
        const formTitle = document.getElementById('form-title');
        const formDesc = document.getElementById('form-desc');
        const submitBtnText = document.getElementById('submit-btn-text');
        
        // Conditional structural fields
        const fieldFullName = document.getElementById('field-fullname');
        const fieldPhone = document.getElementById('field-phone');
        const inputFullName = document.getElementById('fullname');
        const inputPhone = document.getElementById('phone');

        actionInput.value = action;

        if (action === 'signin') {
            btnSignIn.classList.add('active');
            btnCreate.classList.remove('active');
            formTitle.textContent = "Welcome Back";
            formDesc.textContent = "Sign in to your account to continue";
            submitBtnText.textContent = "Sign In";
            
            // Hide registration-specific blocks & drop required logic
            fieldFullName.style.display = 'none';
            fieldPhone.style.display = 'none';
            inputFullName.removeAttribute('required');
            inputPhone.removeAttribute('required');
        } else {
            btnCreate.classList.add('active');
            btnSignIn.classList.remove('active');
            formTitle.textContent = "Join Auto Hub";
            formDesc.textContent = "Create your account to get started";
            submitBtnText.textContent = "Create Account";
            
            // Expose registration blocks & enforce inputs
            fieldFullName.style.display = 'block';
            fieldPhone.style.display = 'block';
            inputFullName.setAttribute('required', '');
            inputPhone.setAttribute('required', '');
        }
    }

    // Default initializer sequence matching screen variant constraints
    document.addEventListener("DOMContentLoaded", function() {
        <?php if (isset($_POST['action']) && $_POST['action'] === 'signin'): ?>
            setFormAction('signin');
        <?php else: ?>
            setFormAction('create');
        <?php endif; ?>
    });
</script>

</body>
</html>
