<?php
    require_once "./../../vendor/autoload.php";
    session_start();
    if(!isset($_SESSION["user"])) header("location: ./../auth/login.php");
    
    require_once "./../../config/path.php";

    $user = $_SESSION["user"];
    
    // Placeholder data - Replace these with your actual Service/Repository calls
    // Example: $myBookings = $BookingSvc->getUserBookings($user->get_id());
    // Example: $incomingRequests = $BookingSvc->getOwnerRequests($user->get_id());
    
    $myBookings = []; 
    $incomingRequests = []; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings | RentalApp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-50 min-h-screen">

    <?php require_once "./../../components/nav.php" ?>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Bookings Dashboard</h1>
            <p class="text-gray-500">Manage your trips and guest requests in one place.</p>
        </div>

        <div class="border-b border-gray-200 mb-8">
            <nav class="flex gap-8" aria-label="Tabs">
                <button onclick="switchTab('my-history')" id="tab-history" 
                    class="tab-btn border-b-2 border-rose-500 py-4 px-1 text-sm font-medium text-rose-600 outline-none">
                    My Booking History
                </button>
                <button onclick="switchTab('client-requests')" id="tab-requests" 
                    class="tab-btn border-b-2 border-transparent py-4 px-1 text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 outline-none">
                    Incoming Client Requests
                </button>
            </nav>
        </div>

        <div id="content-history" class="tab-content block">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Accommodation</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Check-in / Out</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Total Price</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if(empty($myBookings)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                    <i class="fa-solid fa-calendar-xmark text-4xl mb-3 block"></i>
                                    You haven't made any bookings yet.
                                </td>
                            </tr>
                        <?php else: foreach($myBookings as $b): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">House Title</div>
                                    <div class="text-sm text-gray-500">City, Address</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Dates here</td>
                                <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">$0.00</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-700">Pending</span>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div id="content-requests" class="tab-content hidden">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Guest</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Property</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Dates</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if(empty($incomingRequests)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                    <i class="fa-solid fa-inbox text-4xl mb-3 block"></i>
                                    No pending requests for your properties.
                                </td>
                            </tr>
                        <?php else: foreach($incomingRequests as $req): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 mr-3 text-xs font-bold">JD</div>
                                        <div class="text-sm font-medium text-gray-900">Guest Name</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Property Name</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">May 12 - 15</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button onclick="handleRequest(1, 'confirm')" class="text-emerald-600 hover:text-emerald-900 mr-4 font-bold transition">Confirm</button>
                                    <button onclick="handleRequest(1, 'reject')" class="text-rose-600 hover:text-rose-900 font-bold transition">Reject</button>
                                </td>
                            </tr>
                        <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        function switchTab(tab) {
            // Content elements
            const historyContent = document.getElementById('content-history');
            const requestsContent = document.getElementById('content-requests');
            
            // Tab button elements
            const tabHistory = document.getElementById('tab-history');
            const tabRequests = document.getElementById('tab-requests');

            if (tab === 'my-history') {
                // Show History
                historyContent.classList.replace('hidden', 'block');
                requestsContent.classList.replace('block', 'hidden');
                
                // Style Active Tab
                tabHistory.classList.add('border-rose-500', 'text-rose-600');
                tabHistory.classList.remove('border-transparent', 'text-gray-500');
                
                // Style Inactive Tab
                tabRequests.classList.remove('border-rose-500', 'text-rose-600');
                tabRequests.classList.add('border-transparent', 'text-gray-500');
            } else {
                // Show Requests
                requestsContent.classList.replace('hidden', 'block');
                historyContent.classList.replace('block', 'hidden');
                
                // Style Active Tab
                tabRequests.classList.add('border-rose-500', 'text-rose-600');
                tabRequests.classList.remove('border-transparent', 'text-gray-500');
                
                // Style Inactive Tab
                tabHistory.classList.remove('border-rose-500', 'text-rose-600');
                tabHistory.classList.add('border-transparent', 'text-gray-500');
            }
        }

        function handleRequest(id, action) {
            const color = action === 'confirm' ? '#10b981' : '#f43f5e';
            
            Swal.fire({
                title: 'Are you sure?',
                text: `Do you want to ${action} this booking request?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: color,
                cancelButtonColor: '#6b7280',
                confirmButtonText: `Yes, ${action} it!`,
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `../../controllers/bookings/update_status.php?id=${id}&action=${action}`;
                }
            });
        }
    </script>

    <?php if (isset($_SESSION['error'])): ?>
    <script>
        Swal.fire({ icon: 'error', title: 'Oops', text: '<?= $_SESSION['error'] ?>', confirmButtonColor: '#f43f5e' });
    </script>
    <?php unset($_SESSION['error']); endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
    <script>
        Swal.fire({ icon: 'success', title: 'Done!', text: '<?= $_SESSION['success'] ?>', confirmButtonColor: '#10b981' });
    </script>
    <?php unset($_SESSION['success']); endif; ?>

</body>
</html>