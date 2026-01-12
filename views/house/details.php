<?php
    require_once "./../../config/path.php";
    require_once "./../../vendor/autoload.php";

    session_start();

    if(!isset($_SESSION["user"])) header("location: ./../auth/login.php");
    
    $houseId = $_GET["id"] ?? null; 
    if (!$houseId) header("location: " . BASE_URL. "/views/house/houses.php");
    
    use Core\Database;
    use Services\HouseService;
    use Repositories\UserRepository;
    use Repositories\HouseRepository;

    $errors = $_SESSION["errors"] ?? [];
    unset($_SESSION["errors"]);
    
    $HouseSvc = new HouseService(new HouseRepository(Database::get_instance()), new UserRepository(Database::get_instance()));
    
    $house = $HouseSvc->find($houseId); 
    $owner = $HouseSvc->getOwner($houseId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $house->title ?> | Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .flatpickr-calendar { border-radius: 1rem !important; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important; border: 1px solid #f3f4f6 !important; }
        .flatpickr-day.selected { background: #f43f5e !important; border-color: #f43f5e !important; }

        .swiper { 
            width: 100%; 
            height: 500px; 
            border-radius: 1.5rem; 
            background-color: #f3f4f6;
        }

        .swiper-slide {
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }

        .swiper-slide img { 
            width: 100%; 
            height: 100%; 
            object-fit: contain; 
        }
        .swiper-button-next, .swiper-button-prev { 
            background: white; width: 45px; height: 45px; border-radius: 50%; 
            color: #f43f5e !important; box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .swiper-button-next:after, .swiper-button-prev:after { font-size: 1.2rem; font-weight: bold; }
        .swiper-pagination-bullet-active { background: #f43f5e !important; }
    </style>
</head>
<body class="bg-white min-h-screen">

    <?php require_once "./../../components/nav.php" ?>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900"><?= $house->title ?></h1>
            <div class="flex items-center gap-4 mt-2 text-sm text-gray-600">
                <span class="flex items-center gap-1"><i class="fa-solid fa-star text-rose-500"></i> 4.98 · 12 reviews</span>
                <span class="underline font-semibold cursor-pointer"><?= $house->city ?>, <?= $house->address ?></span>
            </div>
        </div>

        <div class="mb-10 relative group">
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    <?php foreach($house->images as $image): ?>
                        <div class="swiper-slide">
                            <img src="http://localhost:8080/kari/src/imgs/<?= $image ?>" alt="Property image">
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-button-next opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="swiper-button-prev opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="swiper-pagination"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
            
            <div class="lg:col-span-2">
                <div class="flex justify-between items-center pb-8 border-b border-gray-100">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">Hosted by <?= $owner->first_name . " " . $owner->last_name ?></h2>
                        <p class="text-gray-500 mt-1"><?= $house->max_guests ?> guests · <?= $house->total_rooms ?> rooms</p>
                    </div>
                    <div class="h-14 w-14 rounded-full bg-rose-100 border border-rose-200 flex items-center justify-center text-rose-600">
                        <i class="fa-solid fa-user text-2xl"></i>
                    </div>
                </div>

                <div class="py-8 space-y-6">
                    <div class="flex items-start gap-4">
                        <i class="fa-solid fa-door-open text-xl text-gray-600 mt-1"></i>
                        <div>
                            <h4 class="font-bold text-gray-900">Self check-in</h4>
                            <p class="text-gray-500 text-sm">Check yourself in with the keypad.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <i class="fa-solid fa-calendar-check text-xl text-gray-600 mt-1"></i>
                        <div>
                            <h4 class="font-bold text-gray-900">Free cancellation for 48 hours</h4>
                            <p class="text-gray-500 text-sm">Full refund if you change your mind.</p>
                        </div>
                    </div>
                </div>

                <div class="py-8 border-t border-gray-100">
                    <p class="text-gray-700 leading-relaxed text-lg">
                        <?= nl2br($house->description) ?>
                    </p>
                </div>
            </div>

            <div class="relative">
                <div class="sticky top-28 p-6 bg-white border border-gray-200 rounded-2xl shadow-xl shadow-gray-100/50">
                    <div class="flex justify-between items-end mb-6">
                        <div>
                            <span class="text-2xl font-bold text-gray-900"><?= $house->price ?>$</span>
                            <span class="text-gray-500">/ night</span>
                        </div>
                        <div class="text-sm font-semibold underline text-gray-500">12 reviews</div>
                    </div>

                    <form action="<?= BASE_URL ?>/controllers/booking/store.php" method="POST" class="space-y-4">
                        <input type="hidden" name="house_id" value="<?= $house->get_id() ?>">
                        
                        <div class="border border-gray-300 rounded-xl overflow-hidden">
                            <div class="grid grid-cols-2 border-b border-gray-300">
                                <div class="p-3 border-r border-gray-300">
                                    <label class="block text-[10px] font-bold uppercase text-gray-900">Check-in</label>
                                    <input required type="text" id="checkin" name="checkin" placeholder="Add date" class="w-full text-sm outline-none bg-transparent">
                                </div>
                                <div class="p-3">
                                    <label class="block text-[10px] font-bold uppercase text-gray-900">Check-out</label>
                                    <input required type="text" id="checkout" name="checkout" placeholder="Add date" class="w-full text-sm outline-none bg-transparent">
                                </div>
                            </div>
                            <div class="p-3">
                                <label class="block text-[10px] font-bold uppercase text-gray-900">Guests</label>
                                <select name="guests" class="w-full text-sm outline-none bg-transparent">
                                    <?php for($i=1; $i <= $house->max_guests; $i++): ?>
                                        <option value="<?= $i ?>"><?= $i ?> Guest<?= $i > 1 ? 's' : '' ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-4 bg-rose-500 text-white font-bold rounded-xl shadow-lg shadow-rose-200 hover:bg-rose-600 transition duration-300">
                            Reserve Now
                        </button>
                    </form>

                    <p class="text-center text-gray-400 text-sm mt-4">You won't be charged yet</p>
                </div>
            </div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const swiper = new Swiper(".mySwiper", {
                loop: true,
                spaceBetween: 10,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                keyboard: {
                    enabled: true,
                },
            });

            const checkinPicker = flatpickr("#checkin", {
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
                minDate: "today",
                onChange: function(selectedDates, dateStr, instance) {
                    checkoutPicker.set('minDate', dateStr);
                }
            });

            const checkoutPicker = flatpickr("#checkout", {
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
                minDate: "today"
            });


            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            const phpErrors = <?php echo json_encode(array_values($errors)); ?>;
            
            phpErrors.forEach((error, index) => {
                Toast.fire({
                    icon: 'error',
                    title: error
                });
            });
        });
    </script>
</body>
</html>