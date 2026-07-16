<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Booking | Capturra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>
</head>
<body class="bg-[#0b0f1a] min-h-screen flex items-center justify-center p-6 font-sans">

    <div class="max-w-md w-full bg-slate-900 rounded-[2.5rem] shadow-2xl border border-slate-800 p-8">
        
        <div class="text-center mb-8">
            <h1 class="text-2xl font-black text-white uppercase tracking-tighter">Fast Booking</h1>
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Capturra Photography</p>
        </div>

        <form id="simpleBookingForm" class="space-y-5">
            <div class="space-y-4">
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-2">Your Name</label>
                    <input type="text" id="name" placeholder="Enter Full Name" required 
                           class="w-full p-4 bg-slate-800 rounded-2xl border border-slate-700 text-white font-bold text-sm outline-none focus:border-purple-500 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-2">Phone Number</label>
                    <input type="tel" id="phone" placeholder="Enter 10 Digit Mobile" required 
                           class="w-full p-4 bg-slate-800 rounded-2xl border border-slate-700 text-white font-bold text-sm outline-none focus:border-purple-500 transition-all">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 mb-2">Event Date</label>
                    <input type="date" id="event_date" required 
                           class="w-full p-4 bg-slate-800 rounded-2xl border border-slate-700 text-white font-bold text-sm outline-none focus:border-purple-500 transition-all [color-scheme:dark]">
                </div>
            </div>

            <div class="bg-slate-800/50 p-5 rounded-3xl border border-slate-800 space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-xs font-black text-white uppercase">Advance Amount</span>
                    <span class="text-xl font-black text-purple-500">₹7,500</span>
                </div>
                <p class="text-[10px] text-slate-500 font-bold leading-relaxed italic">
                    * Paying this advance confirms your booking. The remaining ₹7,500 is payable after the event.
                </p>
            </div>

            <button type="button" onclick="payNow()" 
                    class="w-full py-5 bg-purple-600 text-white rounded-[2rem] font-black text-xs tracking-[0.2em] hover:bg-purple-700 transition-all shadow-xl active:scale-95 uppercase">
                Confirm & Pay Advance
            </button>
        </form>
    </div>

    <script>
        function payNow() {
            const name = document.getElementById('name').value;
            const phone = document.getElementById('phone').value;
            const date = document.getElementById('event_date').value;

            if (!name || !phone || !date) {
                alert("Bhai, saari details bharna zaroori hai!");
                return;
            }

            const options = {
                "key": "rzp_test_SZVNqgecO2gnbp", // Aapki Key
                "amount": 7500 * 100, // INR 7500 in paise
                "currency": "INR",
                "name": "Capturra",
                "description": "Advance Booking Fee",
                "handler": function (response) {
                    // Success hone par redirect
                    window.location.href = `save_booking.php?name=${name}&phone=${phone}&date=${date}&pay_id=${response.razorpay_payment_id}`;
                },
                "prefill": { "name": name, "contact": phone },
                "theme": { "color": "#8b5cf6" }
            };
            const rzp = new Razorpay(options);
            rzp.open();
        }
    </script>
</body>
</html>
