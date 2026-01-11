<?php
    require_once "./../../vendor/autoload.php";
    session_start();
    if(!isset($_SESSION["user"])) header("location: ./../auth/login.php");
    
    require_once "./../../config/path.php";

    $user = $_SESSION["user"];
    $page = $_GET["page"] ?? 1;

    use Core\Database;
    use Services\HouseService;
    use Repositories\UserRepository;
    use Repositories\HouseRepository;

    $House = new HouseService(new HouseRepository(Database::get_instance()), new UserRepository(Database::get_instance()));

    $all_houses = $House->getAllHouses($user->get_id(), $page);
    $total_pages = (int) ((count($all_houses) - 1) / 12) + 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Houses</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 min-h-screen">

    <? require_once "./../../components/nav.php" ?>

    <header class="bg-white border-b border-gray-100 shadow-sm py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="#" method="GET" class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-4">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-location-dot"></i>
                    </span>
                    <input type="text" name="city" placeholder="Where are you going?" 
                        class="w-full pl-10 pr-3 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-500 focus:outline-none text-sm">
                </div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-euro-sign"></i>
                    </span>
                    <input type="number" name="min_price" placeholder="Min Price" 
                        class="w-full pl-10 pr-3 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-500 focus:outline-none text-sm">
                </div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-euro-sign"></i>
                    </span>
                    <input type="number" name="max_price" placeholder="Max Price" 
                        class="w-full pl-10 pr-3 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-500 focus:outline-none text-sm">
                </div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                        <i class="fa-solid fa-users"></i>
                    </span>
                    <input type="number" name="guests" placeholder="Guests" 
                        class="w-full pl-10 pr-3 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-500 focus:outline-none text-sm">
                </div>
                <button type="submit" class="bg-rose-500 text-white font-bold py-3 px-6 rounded-xl hover:bg-rose-600 transition shadow-lg shadow-rose-100 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    Search
                </button>
            </form>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h2 class="text-2xl font-bold text-gray-800 mb-8">Available Accommodations</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php foreach($all_houses as $house): ?>
                <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100">
                    <div class="relative aspect-[4/3] overflow-hidden">
                        <img src="http://localhost:8080/kari/src/imgs/<?= $house->images[0] ?>" alt="House" class="object-cover w-full h-full group-hover:scale-110 transition duration-500">
                        <button class="absolute top-3 right-3 p-2 bg-white/80 backdrop-blur-sm rounded-full text-gray-400 hover:text-rose-500 transition">
                            <i class="fa-solid fa-heart"></i>
                        </button>
                        <div class="absolute bottom-3 left-3 bg-rose-500 text-white text-xs font-bold px-2 py-1 rounded">
                            Available Now
                        </div>
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-bold text-gray-800 text-lg"><?= $house->title ?></h3>
                            <div class="flex items-center text-sm">
                                <i class="fa-solid fa-star text-yellow-400 mr-1"></i>
                                <span class="font-semibold">4.9</span>
                            </div>
                        </div>
                        <p class="text-gray-500 text-sm mb-4 line-clamp-1"><?= $house->city ?> • <?= $house->address ?></p>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                            <div>
                                <span class="text-xl font-bold text-gray-900"><?= $house->price ?>$</span>
                                <span class="text-gray-500 text-sm">/ night</span>
                            </div>
                            <a href="./details.php?id=<?= $house->get_id() ?>" class="text-rose-500 font-semibold text-sm hover:underline">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>

        <div class="mt-16 flex justify-center">
            <nav class="flex items-center gap-2">
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                    <a href="#" class="w-10 h-10 flex items-center justify-center rounded-xl <?= $page === $i ? "bg-rose-500 text-white font-bold shadow-lg shadow-rose-100" : "border border-gray-200 text-gray-600 hover:bg-gray-50 transition" ?>"><?= $i ?></a>
                <?php endfor; ?>
            </nav>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-100 py-10 mt-20">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-500 text-sm">
            &copy; 2026 RentalApp Inc. All rights reserved.
        </div>
    </footer>

</body>
</html>