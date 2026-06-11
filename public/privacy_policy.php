<?php ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Privacy Policy | Capturra</title>

<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">

<style>
body{
    font-family:'Inter',sans-serif;
    background:linear-gradient(135deg,#0f0f13,#1a0a2e);
    color:#e2e0f0;
}

/* glass card */
.card{
    background:rgba(255,255,255,0.05);
    backdrop-filter:blur(18px);
    border:1px solid rgba(255,255,255,0.1);
    border-radius:20px;
    padding:30px;
    transition:0.3s;
}
.card:hover{
    border-color:#7c3aed;
    transform:translateY(-4px);
    box-shadow:0 10px 30px rgba(124,58,237,0.2);
}

/* headings */
.section-title{
    font-size:20px;
    font-weight:600;
    margin-bottom:10px;
    color:white;
}

/* highlight text */
.highlight{
    color:#a855f7;
    font-weight:600;
}

/* button */
.btn{
    background:linear-gradient(135deg,#7c3aed,#a855f7);
    padding:10px 18px;
    border-radius:10px;
    font-size:14px;
    font-weight:600;
    display:inline-block;
    transition:0.3s;
}
.btn:hover{
    transform:scale(1.05);
    box-shadow:0 5px 20px rgba(124,58,237,0.4);
}
</style>

</head>
<body>

<div class="max-w-5xl mx-auto px-6 py-12">

<!-- HEADER -->
<div class="text-center mb-10">
    <h1 class="text-4xl font-extrabold text-white mb-3">
        🔐 Privacy Policy
    </h1>
    <p class="text-gray-400">
        Your privacy matters. Learn how <span class="highlight">Capturra</span> collects and protects your data.
    </p>
</div>

<!-- INTRO -->
<div class="card mb-6">
    <p class="text-gray-300 leading-7">
        At <span class="highlight">Capturra</span>, we respect your privacy and are committed to protecting your personal information.
        This Privacy Policy explains how we collect, use, and safeguard your data when you use our platform.
    </p>
</div>

<!-- INFORMATION WE COLLECT -->
<div class="card mb-6">
    <h2 class="section-title">📥 Information We Collect</h2>
    <ul class="text-gray-300 space-y-2 text-sm">
        <li>• Personal details (name, email, username)</li>
        <li>• Uploaded photos and content</li>
        <li>• Booking and transaction details</li>
        <li>• Usage data (views, likes, interactions)</li>
    </ul>
</div>

<!-- HOW WE USE -->
<div class="card mb-6">
    <h2 class="section-title">⚙️ How We Use Your Information</h2>
    <ul class="text-gray-300 space-y-2 text-sm">
        <li>• To provide and improve our services</li>
        <li>• To personalize your experience</li>
        <li>• To manage bookings and interactions</li>
        <li>• To enhance security and prevent fraud</li>
    </ul>
</div>

<!-- DATA PROTECTION -->
<div class="card mb-6">
    <h2 class="section-title">🛡️ Data Protection</h2>
    <p class="text-gray-300 text-sm leading-7">
        We implement strong security measures including encryption, secure authentication,
        and protected servers to ensure your data remains safe.
    </p>
</div>

<!-- SHARING -->
<div class="card mb-6">
    <h2 class="section-title">🤝 Data Sharing</h2>
    <p class="text-gray-300 text-sm leading-7">
        We do <span class="highlight">NOT sell</span> your personal data.
        Information is only shared when necessary to provide services
        or comply with legal obligations.
    </p>
</div>

<!-- USER RIGHTS -->
<div class="card mb-6">
    <h2 class="section-title">👤 Your Rights</h2>
    <ul class="text-gray-300 space-y-2 text-sm">
        <li>• Access your personal data</li>
        <li>• Update or delete your account</li>
        <li>• Control privacy settings</li>
    </ul>
</div>

<!-- COOKIES -->
<div class="card mb-6">
    <h2 class="section-title">🍪 Cookies</h2>
    <p class="text-gray-300 text-sm">
        We use cookies to improve your browsing experience and analyze platform usage.
    </p>
</div>

<!-- CONTACT -->
<div class="card mb-10">
    <h2 class="section-title">📞 Contact Us</h2>
    <p class="text-gray-300 text-sm">
        If you have any questions about this Privacy Policy, contact us at:
    </p>
    <p class="text-purple-400 mt-2">support@capturra.com</p>
</div>

<!-- BACK BUTTON -->
<div class="text-center">
    <a href="/Capturra/public/client_home.php" class="btn">
        ⬅ Back to Home
    </a>
</div>

</div>

</body>
</html>