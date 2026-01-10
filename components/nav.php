<nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center gap-2">
                <div class="bg-rose-500 p-2 rounded-lg shadow-sm">
                    <i class="fa-solid fa-house-chimney text-white text-lg"></i>
                </div>
                <span class="text-xl font-bold text-gray-800 tracking-tight">Kari</span>
            </div>
            
            <div class="hidden md:flex items-center space-x-8">
                <a href="<?= BASE_URL . "/views/house/houses.php" ?>" class="text-gray-600 hover:text-rose-500 font-medium transition">Home</a>
                <a href="<?= BASE_URL . "/views/house/myhouses.php" ?>" class="text-gray-600 hover:text-rose-500 font-medium transition">My Rentals</a>
                <a href="<?= BASE_URL . "/views/house/houses.php" ?>" class="text-gray-600 hover:text-rose-500 font-medium transition">My Bookings</a>
                <a href="<?= BASE_URL . "/views/house/houses.php" ?>" class="text-gray-600 hover:text-rose-500 font-medium transition">Favorites</a>
                <div class="h-8 w-8 rounded-full bg-rose-100 border border-rose-200 flex items-center justify-center text-rose-600">
                    <i class="fa-solid fa-user text-sm"></i>
                </div>
            </div>
        </div>
    </div>
</nav>