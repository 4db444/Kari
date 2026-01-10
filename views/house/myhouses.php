<?php
    require_once "./../../vendor/autoload.php";
    session_start();
    if(!isset($_SESSION["user"])) header("location: ./../auth/login.php");
    
    require_once "./../../config/path.php";

    $errors = $_SESSION["errors"] ?? [];
    $user = $_SESSION["user"];
    unset($_SESSION["errors"]);

    use Repositories\HouseRepository;
    use Repositories\UserRepository;
    use Services\HouseService;
    use Core\Database;

    $House = new HouseService(new HouseRepository(Database::get_instance()), new UserRepository(Database::get_instance()));

    $my_houses = $House->getMyHouses($user->get_id());
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Rentals | Host Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .modal-active { overflow: hidden; }
        .modal { transition: opacity 0.25s ease; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <?php require_once "./../../components/nav.php" ?>

    <main class="max-w-7xl mx-auto px-4 py-10">
        <div class="flex justify-between items-end mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">My Listings</h1>
                <p class="text-gray-500 mt-1">Manage your properties and track their performance.</p>
            </div>
            <button onclick="toggleModal()" class="bg-rose-500 text-white px-6 py-3 rounded-xl font-bold shadow-lg shadow-rose-100 hover:bg-rose-600 transition flex items-center gap-2">
                <i class="fa-solid fa-plus"></i>
                Add New Rental
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach($my_houses as $house): ?>
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden group">
                    <div class="relative h-48">
                        <img src="http://localhost:8080/kari/src/imgs/<?= $house->images[0] ?>" class="object-cover h-full w-full">
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-lg text-gray-800 capitalize"><?= $house->title ?></h3>
                        <p class="text-gray-500 text-sm mb-4 capitalize"><?= $house->city ?> <?= $house->address ?> • <?= $house->price ?>$ / night</p>
                        <div class="flex gap-2">
                            <a href="./edite.php?id=<?= $house->get_id() ?>" class="flex-1 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition text-center">Edit</a>
                            <form action="<?= BASE_URL . "/controllers/houses/destroy.php" ?>" method="post" class="flex-1">
                                <button class="w-full py-2 border border-red-100 text-red-500 rounded-lg text-sm font-semibold hover:bg-red-50 transition">Delete</button>
                                <input type="hidden" name="id" value="<?= $house->get_id() ?>">
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden group">
                <div class="relative h-48">
                    <img src="https://images.unsplash.com/photo-1493809842364-78817add7ffb?auto=format&fit=crop&w=600" class="w-full h-full object-cover">
                    <div class="absolute top-3 left-3 px-3 py-1 bg-green-500 text-white text-xs font-bold rounded-full">Active</div>
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-lg text-gray-800">Luxury Loft in Downtown</h3>
                    <p class="text-gray-500 text-sm mb-4">Paris, France • 120€ / night</p>
                    <div class="flex gap-2">
                        <button class="flex-1 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold hover:bg-gray-200 transition">Edit</button>
                        <button class="flex-1 py-2 border border-red-100 text-red-500 rounded-lg text-sm font-semibold hover:bg-red-50 transition">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="modal" class="modal opacity-0 pointer-events-none fixed w-full h-full top-0 left-0 flex items-center justify-center z-50">
        <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50" onclick="toggleModal()"></div>
        
        <div class="modal-container bg-white w-11/12 md:max-w-2xl mx-auto rounded-2xl shadow-2xl z-50 overflow-y-auto max-h-[90vh]">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center sticky top-0 bg-white">
                <h3 class="text-xl font-bold text-gray-800">Create New Rental</h3>
                <button onclick="toggleModal()" class="text-gray-400 hover:text-gray-600"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>

            <form action="<?= BASE_URL . "/controllers/houses/store.php" ?>" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Property Title</label>
                        <input required type="text" name="title" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-500 outline-none" placeholder="e.g. Sunny Beachfront Apartment">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">City</label>
                        <input required type="text" name="city" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-500 outline-none" placeholder="City">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Address</label>
                        <input required type="text" name="address" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-500 outline-none" placeholder="Address">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Price / Night</label>
                        <input required type="number" step="0.01" name="price" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-500 outline-none" placeholder="99.99">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Rooms</label>
                        <input required type="number" name="total_rooms" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-500 outline-none" placeholder="3">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Guests</label>
                        <input required type="number" name="max_guests" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-500 outline-none" placeholder="5">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                    <textarea rows="3" name="description" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-500 outline-none"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Property Images</label>
                    <div id="image-inputs-container" class="grid grid-cols-3 gap-3 mb-4">
                        <div id="add-image-btn" onclick="addNewInput()" class="aspect-square bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center text-gray-400 hover:border-rose-400 hover:text-rose-500 cursor-pointer transition">
                            <div class="text-center">
                                <i class="fa-solid fa-plus text-xl block mb-1"></i>
                                <span class="text-[10px] uppercase font-bold">Add Photo</span>
                            </div>
                        </div>
                    </div>
                    <div id="hidden-inputs" class="hidden"></div>
                </div>

                <div class="flex gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="toggleModal()" class="flex-1 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition">Cancel</button>
                    <button type="submit" class="flex-1 py-3 bg-rose-500 text-white font-bold rounded-xl hover:bg-rose-600 transition shadow-lg shadow-rose-100">Publish Listing</button>
                </div>
            </form>
        </div>
    </div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
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

    function toggleModal() {
        const modal = document.getElementById('modal');
        modal.classList.toggle('opacity-0');
        modal.classList.toggle('pointer-events-none');
        document.body.classList.toggle('modal-active');
    }
    
    let inputCount = 0;

    function addNewInput() {
        inputCount++;
        const hiddenContainer = document.getElementById('hidden-inputs');
        const input = document.createElement('input');
        input.type = 'file';
        input.name = 'images[]'; 
        input.id = `file-input-${inputCount}`;
        input.accept = 'image/*';
        
        input.onchange = function(e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const container = document.getElementById('image-inputs-container');
                    const btn = document.getElementById('add-image-btn');
                    const div = document.createElement('div');
                    div.id = `preview-${inputCount}`;
                    div.className = "relative aspect-square rounded-xl overflow-hidden border border-gray-200 group";
                    div.innerHTML = `
                        <img src="${event.target.result}" class="w-full h-full object-cover">
                        <button type="button" onclick="removeImage(${inputCount})" 
                            class="absolute top-1 right-1 bg-red-500 text-white w-6 h-6 rounded-full text-xs flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    `;
                    container.insertBefore(div, btn);
                    hiddenContainer.appendChild(input);
                };
                reader.readAsDataURL(this.files[0]);
            } else {
                input.remove();
            }
        };

        input.click();
    }

    function removeImage(id) {
        const preview = document.getElementById(`preview-${id}`);
        const input = document.getElementById(`file-input-${id}`);
        if(preview) preview.remove();
        if(input) input.remove();
    }
</script>
</body>
</html>