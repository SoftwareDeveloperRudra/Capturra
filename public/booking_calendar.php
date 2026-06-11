<?php
require_once "../config/database.php"; 

$month = isset($_GET['m']) ? $_GET['m'] : date('m');
$year = isset($_GET['y']) ? $_GET['y'] : date('Y');

$firstDayOfMonth = date('w', strtotime("$year-$month-01"));
$daysInMonth = date('t', strtotime("$year-$month-01"));
$monthName = date('F', strtotime("$year-$month-01"));
?>

<!DOCTYPE html>
<html lang="en" id="themeRoot" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Calendar | Capturra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' }</script>
    <style>
        .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 4px; }
        .date-btn { 
            aspect-ratio: 1/1; display: flex; align-items: center; justify-content: center; 
            border-radius: 8px; cursor: pointer; transition: 0.2s; font-weight: 700; font-size: 0.7rem; 
        }
        .selected-date { background-color: #8b5cf6 !important; color: white !important; box-shadow: 0 4px 10px rgba(139, 92, 246, 0.3); }
        #eventOptions { max-height: 0; overflow: hidden; transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        #eventOptions.open { max-height: 400px; padding-bottom: 10px; }
        .rotate-icon { transition: transform 0.3s; }
        .rotate-icon.active { transform: rotate(180deg); }
        .event-radio { display: none; }
        .event-row { 
            display: flex; align-items: center; padding: 12px; border-radius: 12px; 
            cursor: pointer; transition: 0.2s; margin-bottom: 4px; border: 1px solid transparent;
        }
        .dark .event-row { background-color: #1e293b; color: #94a3b8; }
        .light .event-row { background-color: #f1f5f9; color: #475569; }
        .event-radio:checked + .event-row { 
            background-color: rgba(139, 92, 246, 0.1); 
            color: #8b5cf6; 
            border-color: #8b5cf6; 
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-[#070a11] min-h-screen transition-colors duration-500 font-sans p-4">

    <div class="max-w-md mx-auto mb-6 flex justify-between items-center px-2">
        <h1 class="text-xl font-black dark:text-white text-slate-800 tracking-tighter uppercase">Booking Calendar</h1>
        <button onclick="toggleTheme()" id="themeBtn" class="w-12 h-12 rounded-2xl bg-white dark:bg-slate-800 shadow-lg border border-slate-200 dark:border-slate-700 flex items-center justify-center text-lg transition-transform active:scale-90">
            🌙
        </button>
    </div>

    <div class="max-w-md mx-auto bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl border border-slate-100 dark:border-slate-800 p-6">
        
        <form id="bookingForm">
            <input type="hidden" name="selected_date" id="date_input">

            <div class="mb-6">
                <label class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] ml-1">Event Type</label>
                
                <button type="button" onclick="toggleEvents()" class="w-full mt-2 flex justify-between items-center p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 group">
                    <span id="selectedEventText" class="font-bold text-sm dark:text-slate-200 text-slate-700">Select Category</span>
                    <svg class="rotate-icon w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                </button>

                <div id="eventOptions" class="mt-2 space-y-1">
                    <?php 
                    $events = ['Wedding', 'Pre-Wedding', 'Birthday', 'Portraits', 'Engagement', 'Others'];
                    foreach($events as $e) { ?>
                        <label>
                            <input type="radio" name="event_type" value="<?php echo $e; ?>" class="event-radio" onchange="updateEventText('<?php echo $e; ?>')">
                            <div class="event-row text-[10px] font-black uppercase tracking-widest">
                                <span class="w-2 h-2 rounded-full border-2 border-current mr-3"></span>
                                <?php echo $e; ?>
                            </div>
                        </label>
                    <?php } ?>
                </div>
            </div>

            <div class="bg-slate-50 dark:bg-slate-800/40 p-4 rounded-3xl mb-6 border border-slate-100 dark:border-slate-700/30">
                <div class="flex justify-between items-center mb-4 px-1">
                    <span class="font-black dark:text-white text-slate-800 text-xs uppercase tracking-tighter"><?php echo $monthName . " " . $year; ?></span>
                </div>

                <div class="calendar-grid text-[8px] font-black text-slate-400 mb-2 text-center tracking-widest">
                    <div>S</div><div>M</div><div>T</div><div>W</div><div>T</div><div>F</div><div>S</div>
                </div>

                <div class="calendar-grid">
                    <?php
                    for ($i = 0; $i < $firstDayOfMonth; $i++) echo "<div class='date-btn empty'></div>";
                    for ($day = 1; $day <= $daysInMonth; $day++) {
                        $fullDate = "$year-$month-" . str_pad($day, 2, '0', STR_PAD_LEFT);
                        echo "<div onclick='selectDate(this, \"$fullDate\")' class='date-btn bg-white dark:bg-slate-800 dark:text-slate-300 shadow-sm border border-slate-100 dark:border-slate-700/50'>$day</div>";
                    }
                    ?>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mb-6">
                <input type="text" id="cust_name" name="name" placeholder="Full Name" required class="w-full p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl outline-none focus:ring-2 focus:ring-purple-500 dark:text-white text-xs font-bold border-none transition-all">
                <input type="text" id="cust_phone" name="phone" placeholder="Phone" required class="w-full p-4 bg-slate-50 dark:bg-slate-800 rounded-2xl outline-none focus:ring-2 focus:ring-purple-500 dark:text-white text-xs font-bold border-none transition-all">
            </div>

            <div class="mt-8">
                <button type="button" onclick="goToPayment()" class="w-full text-center bg-purple-600 hover:bg-purple-700 text-white font-bold py-4 px-8 rounded-2xl transition-all shadow-lg shadow-purple-200">
                    Confirm Booking & Proceed to Payment
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleTheme() {
            const root = document.getElementById('themeRoot');
            const btn = document.getElementById('themeBtn');
            if (root.classList.contains('dark')) {
                root.classList.remove('dark');
                btn.innerText = "☀️";
            } else {
                root.classList.add('dark');
                btn.innerText = "🌙";
            }
        }

        function toggleEvents() {
            const options = document.getElementById('eventOptions');
            const icon = document.querySelector('.rotate-icon');
            options.classList.toggle('open');
            icon.classList.toggle('active');
        }

        function updateEventText(val) {
            document.getElementById('selectedEventText').innerText = val;
            setTimeout(toggleEvents, 300);
        }

        function selectDate(el, date) {
            document.querySelectorAll('.date-btn').forEach(d => d.classList.remove('selected-date'));
            el.classList.add('selected-date');
            document.getElementById('date_input').value = date;
        }

        // Redirect Logic
        function goToPayment() {
            const name = document.getElementById('cust_name').value;
            const phone = document.getElementById('cust_phone').value;
            const date = document.getElementById('date_input').value;
            const event = document.getElementById('selectedEventText').innerText;

            if (!name || !phone || !date || event === "Select Category") {
                alert("Please fill all details and select a booking date!");
                return;
            }

            // Redirecting to payment.php with data
            const url = `payment.php?name=${encodeURIComponent(name)}&phone=${encodeURIComponent(phone)}&date=${encodeURIComponent(date)}&event=${encodeURIComponent(event)}`;
            window.location.href = url;
        }
    </script>
</body>
</html>