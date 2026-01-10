<?php
    require_once "./../../vendor/autoload.php";
    session_start();
    if(!isset($_SESSION["user"])) header("location: ./../auth/login.php");
    if(!isset($_GET["id"])) header("location: ./myhouses.php");

    require_once "./../../config/path.php";

    $house = [
        'id' => 12,
        'title' => 'Luxury Loft in Downtown',
        'city' => 'Paris',
        'address' => '15 Rue de Rivoli',
        'price' => 120.00,
        'total_rooms' => 3,
        'max_guests' => 4,
        'description' => 'A beautiful loft located in the heart of Paris.',
        'images' => [
            ['id' => 101, 'url' => 'https://images.unsplash.com/photo-1493809842364-78817add7ffb'],
            ['id' => 102, 'url' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688']
        ]
    ];

    $user = $_SESSION["user"];
    $errors = $_SESSION["errors"] ?? [];
    unset($_SESSION["errors"]);

    use Repositories\HouseRepository;
    use Repositories\UserRepository;
    use Services\HouseService;
    use Core\Database;

    $House = new HouseService(new HouseRepository(Database::get_instance()), new UserRepository(Database::get_instance()));

    $house = $House->find($_GET["id"]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Rental | <?= $house->title ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        #toast-container { position: fixed; top: 1rem; right: 1rem; z-index: 9999; display: flex; flex-direction: column; gap: 0.5rem; }
        .custom-toast { background: white; border-left: 4px solid #f43f5e; padding: 1rem; border-radius: 0.75rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 0.75rem; animation: slideIn 0.3s ease; }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    </style>
</head>
<body class="bg-gray-50">

    <div id="toast-container"></div>
    <?php require_once "./../../components/nav.php" ?>

    <main class="max-w-4xl mx-auto px-4 py-12">
        <div class="flex items-center gap-4 mb-8">
            <a href="dashboard.php" class="h-10 w-10 flex items-center justify-center rounded-full bg-white border border-gray-200 text-gray-600 hover:text-rose-500 transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Property</h1>
                <p class="text-gray-500 text-sm">Update your listing details and photos.</p>
            </div>
        </div>

        <form action="<?= BASE_URL . "/controllers/houses/store.php" ?>" method="POST" enctype="multipart/form-data" class="space-y-8">
            <input type="hidden" name="id" value="<?= $house->get_id() ?>">

            <div class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm space-y-6">
                <h2 class="text-lg font-bold text-gray-800 border-b pb-4">General Information</h2>
                
                <div class="grid grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Property Title</label>
                        <input type="text" name="title" value="<?= $house->title ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">City</label>
                        <input type="text" name="city" value="<?= $house->city ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Address</label>
                        <input type="text" name="address" value="<?= $house->address ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-500 outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Price / Night ($)</label>
                        <input type="number" step="0.01" name="price" value="<?= $house->price ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Rooms</label>
                        <input type="number" name="total_rooms" value="<?= $house->total_rooms ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Max Guests</label>
                        <input type="number" name="max_guests" value="<?= $house->max_guests ?>" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Description</label>
                    <textarea rows="4" name="description" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-xl outline-none"><?= $house->description ?></textarea>
                </div>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-gray-200 shadow-sm space-y-6">
                <h2 class="text-lg font-bold text-gray-800 border-b pb-4">Property Media</h2>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4" id="image-inputs-container">
                    <?php foreach($house->images as $img): ?>
                        <div class="relative aspect-square rounded-xl overflow-hidden border border-gray-200 group" id="existing-img-<?= $img ?>">
                            <img src="http://localhost:8080/kari/src/imgs/<?= $img ?>" class="w-full h-full object-cover">
                            <button type="button" onclick="removeExisting('<?= $img ?>')" class="absolute top-2 right-2 bg-red-500 text-white w-7 h-7 rounded-full flex items-center justify-center shadow-lg hover:bg-red-600 transition">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>

                    <div id="add-image-btn" onclick="addNewInput()" class="aspect-square bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center text-gray-400 hover:border-rose-400 hover:text-rose-500 cursor-pointer transition">
                        <div class="text-center">
                            <i class="fa-solid fa-plus text-xl block mb-1"></i>
                            <span class="text-[10px] uppercase font-bold">Upload New</span>
                        </div>
                    </div>
                </div>
                <div id="hidden-inputs" class="hidden"></div>
            </div>

            <div class="flex items-center justify-end gap-4">
                <a href="./myhouses.php" class="px-8 py-3 font-bold text-gray-500 hover:text-gray-700">Discard Changes</a>
                <button type="submit" class="px-10 py-3 bg-rose-500 text-white font-bold rounded-xl shadow-lg shadow-rose-100 hover:bg-rose-600 transition">
                    Save Updates
                </button>
            </div>
        </form>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const phpErrors = <?php echo json_encode(array_values($errors)); ?>;
            const container = document.getElementById('toast-container');
            phpErrors.forEach((error, index) => {
                setTimeout(() => {
                    const toast = document.createElement('div');
                    toast.className = "custom-toast border border-gray-100";
                    toast.innerHTML = `<i class="fa-solid fa-circle-exclamation text-rose-500"></i><div class="flex-1"><p class="text-xs text-gray-500">${error}</p></div>`;
                    container.appendChild(toast);
                    setTimeout(() => toast.remove(), 5000);
                }, index * 200);
            });
        });

        function removeExisting(id) {
            Swal.fire({
                title: 'Remove photo?',
                text: "This image will be removed once you save updates.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f43f5e',
                confirmButtonText: 'Yes, remove it'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`existing-img-${id}`).remove();
                }
            })

            const hiddenContainer = document.getElementById('hidden-inputs');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'existing_images[]'; 
            input.value = id;
            hiddenContainer.appendChild(input)
        }

        // Add New Input Logic (Same as creation page)
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
                            <button type="button" onclick="removeNew(${inputCount})" 
                                class="absolute top-2 right-2 bg-gray-900/50 text-white w-7 h-7 rounded-full flex items-center justify-center hover:bg-red-500 transition">
                                <i class="fa-solid fa-xmark text-xs"></i>
                            </button>
                        `;
                        container.insertBefore(div, btn);
                        hiddenContainer.appendChild(input);
                    };
                    reader.readAsDataURL(this.files[0]);
                } else { input.remove(); }
            };
            input.click();
        }

        function removeNew(id) {
            document.getElementById(`preview-${id}`).remove();
            document.getElementById(`file-input-${id}`).remove();
        }
    </script>
</body>
</html>