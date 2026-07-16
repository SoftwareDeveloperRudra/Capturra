<?php
include("../config/database.php");

// 1. Get ID from URL
$p_id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : 0;

if ($p_id == 0) {
    die("Invalid ID. Please provide a photographer ID in the URL.");
}

// 2. Fetch User Details
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$p_id'");
$user_data = mysqli_fetch_assoc($user_query);

if (!$user_data) {
    die("Photographer not found.");
}

$username = htmlspecialchars($user_data['username'] ?? 'Creative');
$full_name = htmlspecialchars($user_data['full_name'] ?? $username); 
$photographer_email = htmlspecialchars($user_data['email'] ?? '');
$bio = htmlspecialchars($user_data['bio'] ?? 'Visual Storyteller based in India.');

// 3. Fetch Photos
$photos_query = mysqli_query($conn, "SELECT * FROM photos WHERE user_id = '$p_id' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $full_name; ?> | Portfolio</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,900;1,900&family=Inter:wght@300;400;700;900&display=swap" rel="stylesheet">
    <style>
        :root { --bg-color: #050505; --accent: #8b5cf6; }
        body { background-color: #050505; background-image: radial-gradient(circle at top right, #1e1b4b, #050505 60%); color: #fff; font-family: 'Inter', sans-serif; overflow-x: hidden; }
        h1, h2, h3 { font-family: 'Playfair Display', serif; }
        
        /* Glassmorphism & Hover Effects */
        .glass-header { 
            background: rgba(255, 255, 255, 0.03); 
            backdrop-filter: blur(15px); 
            border: 1px solid rgba(255, 255, 255, 0.08); 
            border-radius: 28px;
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .glass-header:hover {
            transform: translateY(-12px) scale(1.02);
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(139, 92, 246, 0.4);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5), 0 0 30px rgba(139, 92, 246, 0.15);
        }

        /* Video Style Glowing Avatar */
        .profile-glow {
            border: 2px solid #8b5cf6;
            box-shadow: 0 0 25px rgba(139, 92, 246, 0.6);
            transition: all 0.6s ease;
        }
        .profile-glow:hover {
            box-shadow: 0 0 50px rgba(139, 92, 246, 0.9);
            transform: scale(1.05);
        }

        .masonry { column-count: 1; column-gap: 1.5rem; }
        @media (min-width: 768px) { .masonry { column-count: 2; } }
        @media (min-width: 1024px) { .masonry { column-count: 3; } }
        
        .reveal { opacity: 0; transform: translateY(30px); transition: all 0.8s ease-out; }
        .reveal.active { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body>

    <nav class="p-8 flex justify-between items-center sticky top-0 z-40 backdrop-blur-sm">
        <span class="text-[10px] uppercase tracking-[0.4em] font-black text-purple-400">Capturra &copy; 2026</span>
        <a href="photographer_home.php" class="text-[10px] uppercase tracking-widest font-black hover:text-purple-500 transition-colors">Back</a>
    </nav>

    <header class="max-w-6xl mx-auto px-6 pt-24 pb-16 text-center reveal">
        <div class="mb-10 inline-block">
            <div class="profile-glow w-28 h-28 rounded-full flex items-center justify-center text-3xl overflow-hidden bg-zinc-900">
                👤
            </div>
        </div>
        
        <div class="flex items-center justify-center space-x-4 mb-4">
            <span class="h-[1px] w-8 bg-purple-500/50"></span>
            <p class="text-zinc-400 uppercase tracking-[0.4em] text-[10px] font-bold">Creative Photographer</p>
            <span class="h-[1px] w-8 bg-purple-500/50"></span>
        </div>

        <p class="text-zinc-300 italic text-xl">"<?php echo $bio; ?>"</p>
    </header>

    <section class="max-w-5xl mx-auto px-6 py-12 grid grid-cols-2 md:grid-cols-4 gap-6 reveal">
        <div class="glass-header p-8 text-center">
            <h4 class="text-4xl font-black text-white">12+</h4>
            <p class="text-[9px] uppercase tracking-widest text-zinc-500 mt-2 font-bold">Projects</p>
        </div>
        <div class="glass-header p-8 text-center">
            <h4 class="text-4xl font-black text-white">05+</h4>
            <p class="text-[9px] uppercase tracking-widest text-zinc-500 mt-2 font-bold">Awards</p>
        </div>
        <div class="glass-header p-8 text-center">
            <h4 class="text-4xl font-black text-white">50+</h4>
            <p class="text-[9px] uppercase tracking-widest text-zinc-500 mt-2 font-bold">Clients</p>
        </div>
        <div class="glass-header p-8 text-center">
            <h4 class="text-4xl font-black text-white">03</h4>
            <p class="text-[9px] uppercase tracking-widest text-zinc-500 mt-2 font-bold">Years Exp</p>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-6 py-16 grid grid-cols-1 md:grid-cols-2 gap-10 reveal">
        <div class="glass-header p-10">
            <h3 class="text-4xl italic mb-6">About Me</h3>
            <p class="text-zinc-400 text-sm leading-relaxed">
                I am a visual storyteller dedicated to capturing raw, authentic moments. With a background in design and a passion for lighting, I create memories that resonate with emotion.
            </p>
        </div>
        <div class="glass-header p-10">
            <h3 class="text-4xl italic mb-6">My Approach</h3>
            <p class="text-zinc-400 text-sm leading-relaxed">
                My philosophy is simple: **Observe, don't intrude.** I use the AEIOU framework to understand the environment and people before I hit the shutter.
            </p>
        </div>
    </section>

    <section class="max-w-6xl mx-auto px-6 py-20 text-center reveal">
        <h3 class="text-4xl italic mb-12">What You Will Find Here</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <div>
                <p class="text-3xl mb-4">📷</p>
                <h4 class="text-[10px] font-black uppercase tracking-widest mb-2">Cinematic Gallery</h4>
                <p class="text-zinc-500 text-xs">A curated collection of my best work.</p>
            </div>
            <div>
                <p class="text-3xl mb-4">🎞️</p>
                <h4 class="text-[10px] font-black uppercase tracking-widest mb-2">Behind the Scenes</h4>
                <p class="text-zinc-500 text-xs">The creative journey behind every shot.</p>
            </div>
            <div>
                <p class="text-3xl mb-4">💼</p>
                <h4 class="text-[10px] font-black uppercase tracking-widest mb-2">Professional Services</h4>
                <p class="text-zinc-500 text-xs">Direct access to booking and inquiries.</p>
            </div>
        </div>
    </section>

    <section class="max-w-[1400px] mx-auto px-6 pb-32 reveal">
        <div class="flex justify-between items-center mb-12">
            <h3 class="text-5xl italic">Portfolio Works</h3>
            <div class="flex space-x-4 text-[9px] font-black uppercase tracking-widest text-zinc-500">
                <span class="text-white border-b border-purple-500">All</span>
                <span>Events</span>
                <span>Portraits</span>
            </div>
        </div>
        <div class="masonry">
            <?php while($row = mysqli_fetch_assoc($photos_query)): ?>
                <div class="masonry-item group relative overflow-hidden rounded-[2rem] bg-zinc-900 reveal cursor-pointer" 
                     onclick="openLightbox('/Capturra/uploads/<?php echo htmlspecialchars($row['image']); ?>', 'Gallery Moment')">
                    <img src="/Capturra/uploads/<?php echo htmlspecialchars($row['image']); ?>" class="w-full h-auto transition-all duration-700 group-hover:scale-110">
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <section class="max-w-5xl mx-auto px-6 py-24 border-t border-white/5 reveal">
        <h2 class="text-6xl italic mb-12">Work with Me</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-16">
            <div class="space-y-6">
                <p class="text-zinc-400">Ready to bring your vision to life? Drop a message below.</p>
                <div class="p-6 glass-header border-l-4 border-purple-500 bg-purple-500/5">
                    <p class="text-[10px] font-black uppercase tracking-widest text-purple-400">Booking Policy</p>
                    <p class="text-zinc-500 text-xs mt-2">50% Advance payment required to confirm the slot.</p>
                </div>
            </div>
            <form class="space-y-8">
                <input type="text" placeholder="Name" class="w-full bg-transparent border-b border-zinc-800 py-3 outline-none focus:border-purple-500 transition-colors">
                <input type="email" placeholder="Email" class="w-full bg-transparent border-b border-zinc-800 py-3 outline-none focus:border-purple-500 transition-colors">
                <textarea placeholder="Message" rows="3" class="w-full bg-transparent border-b border-zinc-800 py-3 outline-none focus:border-purple-500 transition-colors"></textarea>
                <button type="submit" class="px-10 py-4 bg-white text-black font-black text-[10px] uppercase tracking-widest rounded-full hover:bg-purple-600 hover:text-white transition-all">Send Message</button>
            </form>
        </div>
    </section>

    <div id="lightbox" class="fixed inset-0 z-[1000] bg-black/95 hidden flex-col items-center justify-center p-6 backdrop-blur-xl">
        <button onclick="closeLightbox()" class="absolute top-10 right-10 text-white text-4xl">&times;</button>
        <img id="lightbox-img" src="" class="max-w-full max-h-[80vh] object-contain rounded-xl">
    </div>

    <script>
        function reveal() {
            var reveals = document.querySelectorAll(".reveal");
            for (var i = 0; i < reveals.length; i++) {
                var windowHeight = window.innerHeight;
                var elementTop = reveals[i].getBoundingClientRect().top;
                if (elementTop < windowHeight - 100) { reveals[i].classList.add("active"); }
            }
        }
        window.addEventListener("scroll", reveal);
        window.addEventListener("load", reveal);

        function openLightbox(src) {
            document.getElementById('lightbox-img').src = src;
            document.getElementById('lightbox').classList.replace('hidden', 'flex');
        }
        function closeLightbox() {
            document.getElementById('lightbox').classList.replace('flex', 'hidden');
        }
    </script>
</body>
</html>
