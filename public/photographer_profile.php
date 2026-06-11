<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/Capturra/includes/auth.php";
requireRole("photographer"); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Capturra | Professional Identity</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background: #f8fafc;
            min-height: 100vh;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.05);
        }
        .input-field {
            transition: all 0.25s ease;
        }
        .input-field:focus {
            background: white;
            border-color: #6366f1;
            box-shadow: 0 4px 20px -2px rgba(99, 102, 241, 0.15);
            transform: translateY(-1px);
        }
    </style>
</head>
<body class="text-slate-900 antialiased py-12 px-4">

    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h1 class="text-4xl font-extrabold tracking-tight text-slate-900">Account Settings</h1>
                <p class="text-slate-500 font-medium mt-1">Manage your public presence and booking details.</p>
            </div>
            <a href="/Capturra/public/photographer_home.php" class="px-5 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition-all shadow-sm">
                ← Dashboard
            </a>
        </div>

        <div class="glass-card rounded-[2.5rem] overflow-hidden grid grid-cols-1 lg:grid-cols-12">
            
            <div class="lg:col-span-4 bg-indigo-600 p-10 text-white flex flex-col justify-between">
                <div>
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h2 class="text-2xl font-bold mb-4">Public Profile</h2>
                    <p class="text-indigo-100 text-sm leading-relaxed opacity-80">These details will be visible to clients on your portfolio page. High-quality bios often lead to 40% more inquiries.</p>
                </div>
                <div class="mt-10 p-4 bg-white/10 rounded-2xl border border-white/10">
                    <p class="text-[10px] font-black uppercase tracking-widest opacity-60 mb-2">Pro Tip</p>
                    <p class="text-xs italic text-indigo-50">"Mention your specific photography gear to build technical trust with commercial clients."</p>
                </div>
            </div>

            <form id="profileForm" class="lg:col-span-8 p-10 space-y-8 bg-white/50">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 ml-1">Full Name</label>
                        <input type="text" name="name" placeholder="John Doe" 
                            class="input-field w-full px-6 py-4 bg-slate-100/50 border-2 border-transparent rounded-2xl outline-none text-slate-800 font-semibold">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 ml-1">Phone Number</label>
                        <input type="tel" name="phone" placeholder="+91 00000 00000" 
                            class="input-field w-full px-6 py-4 bg-slate-100/50 border-2 border-transparent rounded-2xl outline-none text-slate-800 font-semibold">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 ml-1">Professional Bio</label>
                    <textarea name="bio" rows="4" placeholder="Describe your aesthetic and vision..." 
                        class="input-field w-full px-6 py-4 bg-slate-100/50 border-2 border-transparent rounded-2xl outline-none resize-none text-slate-700"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 ml-1">City / Base</label>
                        <input type="text" name="city" placeholder="Mumbai, India" 
                            class="input-field w-full px-6 py-4 bg-slate-100/50 border-2 border-transparent rounded-2xl outline-none">
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 ml-1">Years of Experience</label>
                        <input type="number" name="experience_years" placeholder="0" 
                            class="input-field w-full px-6 py-4 bg-slate-100/50 border-2 border-transparent rounded-2xl outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-2 ml-1">Commercial Day Rate</label>
                    <div class="relative">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 font-bold text-slate-900">₹</span>
                        <input type="number" name="price_per_day" placeholder="0" 
                            class="input-field w-full pl-12 pr-6 py-5 bg-slate-100/50 border-2 border-transparent rounded-2xl outline-none text-xl font-bold text-slate-900">
                    </div>
                </div>

                <button type="submit" id="submitBtn" 
                    class="w-full py-5 bg-slate-900 text-white rounded-2xl font-bold shadow-2xl shadow-slate-200 hover:bg-black hover:-translate-y-1 transition-all flex items-center justify-center group">
                    <span>Synchronize Profile</span>
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </button>
            </form>
        </div>
    </div>

    <script>
        const form = document.getElementById("profileForm");
        const submitBtn = document.getElementById("submitBtn");

        // Load Profile
        fetch("/Capturra/api/photographer/get_profile.php", { credentials: "include" })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.data) {
                // Mapping sended image data + profile data
                form.name.value = data.data.name || "";
                form.phone.value = data.data.phone || "";
                form.bio.value = data.data.bio || "";
                form.city.value = data.data.city || "";
                form.price_per_day.value = data.data.price_per_day || "";
                form.experience_years.value = data.data.experience_years || "";
            }
        });

        // Save Profile
        form.addEventListener("submit", function(e) {
            e.preventDefault();
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = "Processing...";

            fetch("/Capturra/api/photographer/save_profile.php", {
                method: "POST",
                body: new FormData(form),
                credentials: "include"
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                alert(data.message);
            })
            .catch(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                alert("Network error.");
            });
        });
    </script>
</body>
</html>