<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us | Capturra</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{
    background: radial-gradient(circle at top,#1a0a2e,#0f0f13);
    font-family: 'Inter', sans-serif;
    color:white;
}

/* Glass Card */
.glass{
    background: rgba(255,255,255,0.05);
    backdrop-filter: blur(20px);
    border:1px solid rgba(255,255,255,0.1);
    border-radius:20px;
}

/* Inputs */
.input{
    width:100%;
    padding:12px;
    border-radius:10px;
    background:#16161f;
    border:1px solid #2a2a3e;
    outline:none;
    color:white;
    transition:0.3s;
}

.input:focus{
    border-color:#a855f7;
    box-shadow:0 0 10px rgba(168,85,247,0.4);
}

/* Button */
.btn{
    background: linear-gradient(135deg,#7c3aed,#a855f7);
    padding:12px;
    border-radius:10px;
    width:100%;
    font-weight:bold;
    transition:0.3s;
}

.btn:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 30px rgba(124,58,237,0.5);
}

/* Card hover */
.card-hover:hover{
    transform:translateY(-5px);
    box-shadow:0 10px 40px rgba(124,58,237,0.3);
    transition:0.3s;
}
</style>

</head>

<body>

<div class="max-w-6xl mx-auto p-6">

<!-- HEADER -->
<div class="text-center mb-10">
    <h1 class="text-4xl font-bold mb-2">Contact Us</h1>
    <p class="text-gray-400">We’d love to hear from you 💜</p>
</div>

<div class="grid md:grid-cols-2 gap-8">

<!-- LEFT INFO -->
<div class="space-y-6">

    <div class="glass p-6 card-hover">
        <h2 class="text-xl font-semibold mb-3">📍 Address</h2>
        <p class="text-gray-400">Ahmedabad, India</p>
    </div>

    <div class="glass p-6 card-hover">
        <h2 class="text-xl font-semibold mb-3">📧 Email</h2>
        <p class="text-gray-400">support@capturra.com</p>
    </div>

    <div class="glass p-6 card-hover">
        <h2 class="text-xl font-semibold mb-3">📞 Phone</h2>
        <p class="text-gray-400">+91 9876543210</p>
    </div>

    <!-- SOCIAL -->
    <div class="glass p-6 card-hover">
        <h2 class="text-xl font-semibold mb-3">🌐 Follow Us</h2>
        <div class="flex gap-4 text-xl">
            <i class="fab fa-instagram hover:text-purple-400 cursor-pointer"></i>
            <i class="fab fa-facebook hover:text-purple-400 cursor-pointer"></i>
            <i class="fab fa-twitter hover:text-purple-400 cursor-pointer"></i>
        </div>
    </div>

</div>

<!-- RIGHT FORM -->
<div class="glass p-8">

<form id="contactForm" class="space-y-4">

<input type="text" placeholder="Your Name" class="input" required>
<input type="email" placeholder="Your Email" class="input" required>

<textarea rows="5" placeholder="Your Message..." class="input" required></textarea>

<button type="submit" class="btn">Send Message</button>

</form>

<p id="successMsg" class="hidden text-green-400 mt-4">
✅ Message sent successfully!
</p>

</div>

</div>

</div>

<script>

// fake submit (UI only)
document.getElementById("contactForm").addEventListener("submit", function(e){
    e.preventDefault();
    document.getElementById("successMsg").classList.remove("hidden");
    this.reset();
});

</script>

</body>
</html>