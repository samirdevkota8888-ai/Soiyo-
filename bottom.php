<!-- Bottom Nav -->
    <div class="fixed bottom-0 left-0 w-full bg-white shadow-[0_-2px_10px_rgba(0,0,0,0.1)] flex justify-around py-3 z-40 text-gray-500">
        <a href="index.php" class="flex flex-col items-center hover:text-blue-600 <?php echo basename($_SERVER['PHP_SELF'])=='index.php'?'text-blue-600':'';?>">
            <i class="fas fa-home text-xl"></i>
            <span class="text-xs mt-1">Home</span>
        </a>
        <a href="cart.php" class="flex flex-col items-center hover:text-blue-600 <?php echo basename($_SERVER['PHP_SELF'])=='cart.php'?'text-blue-600':'';?>">
            <i class="fas fa-shopping-cart text-xl"></i>
            <span class="text-xs mt-1">Cart</span>
        </a>
        <a href="order.php" class="flex flex-col items-center hover:text-blue-600 <?php echo basename($_SERVER['PHP_SELF'])=='order.php'?'text-blue-600':'';?>">
            <i class="fas fa-receipt text-xl"></i>
            <span class="text-xs mt-1">Orders</span>
        </a>
        <a href="profile.php" class="flex flex-col items-center hover:text-blue-600 <?php echo basename($_SERVER['PHP_SELF'])=='profile.php'?'text-blue-600':'';?>">
            <i class="fas fa-user text-xl"></i>
            <span class="text-xs mt-1">Profile</span>
        </a>
    </div>

    <!-- ==================== SUPPORT CHAT WIDGET ==================== -->
    
    <!-- 1. Floating Button -->
    <button onclick="toggleChat()" class="fixed bottom-20 right-4 bg-green-500 text-white w-14 h-14 rounded-full shadow-lg z-50 flex items-center justify-center hover:bg-green-600 transition animate-bounce">
        <i class="fas fa-comment-dots text-2xl"></i>
    </button>

    <!-- 2. Chat Box (Hidden by default) -->
    <div id="supportChat" class="fixed bottom-24 right-4 w-72 bg-white rounded-xl shadow-2xl z-50 hidden border border-gray-200 overflow-hidden">
        <!-- Header -->
        <div class="bg-green-600 text-white p-4 flex justify-between items-center">
            <div>
                <h3 class="font-bold">Need Help?</h3>
                <p class="text-xs opacity-90">Chat with us directly!</p>
            </div>
            <button onclick="toggleChat()" class="text-white hover:text-gray-200"><i class="fas fa-times"></i></button>
        </div>

        <!-- Body -->
        <div class="p-4 h-40 bg-gray-50 overflow-y-auto">
            <div class="bg-white p-3 rounded-lg shadow-sm border text-sm text-gray-700 mb-2 inline-block">
                Namaste! 🙏<br>Kasari help garna sakchhu?
            </div>
        </div>

        <!-- Footer (Input) -->
        <div class="p-3 border-t bg-white flex gap-2">
            <input type="text" id="chatMsg" placeholder="Type message..." class="flex-1 border rounded-full px-3 py-2 text-sm outline-none focus:border-green-500">
            <button onclick="sendToWhatsApp()" class="bg-green-600 text-white w-9 h-9 rounded-full flex items-center justify-center shadow">
                <i class="fas fa-paper-plane text-xs"></i>
            </button>
        </div>
    </div>

    <script>
        // --- SETTINGS: CHANGE YOUR NUMBER HERE ---
        const ADMIN_PHONE = "9779708614523"; // यहाँ आफ्नो नम्बर राख्नुहोस (Example: 9779812345678)

        function toggleChat() {
            const chat = document.getElementById('supportChat');
            if (chat.classList.contains('hidden')) {
                chat.classList.remove('hidden');
            } else {
                chat.classList.add('hidden');
            }
        }

        function sendToWhatsApp() {
            const msg = document.getElementById('chatMsg').value;
            if(msg.trim() === "") return;

            // Open WhatsApp
            const url = `https://wa.me/${ADMIN_PHONE}?text=${encodeURIComponent(msg)}`;
            window.open(url, '_blank');
            
            // Clear input
            document.getElementById('chatMsg').value = "";
            toggleChat();
        }
    </script>

</body>
</html>