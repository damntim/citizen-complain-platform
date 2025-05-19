<?php

ob_start();
require_once "../../db_setup.php";


if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    echo "<script>
        alert('You must log in first as an administrator.');
        window.location.href = '../../index.php';
    </script>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Institution Management</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        
        <?php include 'sidebar.php'; ?>

        
        <div class="flex-1 overflow-auto p-6">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-blue-600 text-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-semibold">Total Institutions</h3>
                    <?php
                    $inst_query = "SELECT COUNT(*) as total FROM institutions";
                    $inst_result = mysqli_query($conn, $inst_query);
                    $inst_count = mysqli_fetch_assoc($inst_result)['total'];
                    ?>
                    <p class="text-3xl"><?php echo $inst_count; ?></p>
                </div>
                <div class="bg-yellow-400 text-gray-800 p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-semibold">Total Services</h3>
                    <?php
                    $serv_query = "SELECT COUNT(*) as total FROM services";
                    $serv_result = mysqli_query($conn, $serv_query);
                    $serv_count = mysqli_fetch_assoc($serv_result)['total'];
                    ?>
                    <p class="text-3xl"><?php echo $serv_count; ?></p>
                </div>
            </div>

            
            <div class="bg-white rounded-lg shadow-lg">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex">
                        <button class="tab-button w-1/2 py-4 px-1 text-center border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-green-500 focus:outline-none active" data-tab="institutions">Institutions</button>
                        <button class="tab-button w-1/2 py-4 px-1 text-center border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-green-500 focus:outline-none" data-tab="services">Services</button>
                    </nav>
                </div>

                
                <div id="institutions" class="tab-content p-6">
                    <div class="flex justify-between items-center mb-4">
                        <input type="text" id="inst-search" class="w-full md:w-1/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search by name...">
                        <button id="add-inst-btn" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">Add Institution</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border">
                            <thead>
                                <tr class="bg-blue-100">
                                    <th class="py-2 px-4 border">ID</th>
                                    <th class="py-2 px-4 border">Name</th>
                                    <th class="py-2 px-4 border">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="inst-table-body">
                                <?php
                                $query = "SELECT * FROM institutions";
                                $result = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>
                                        <td class='py-2 px-4 border'>{$row['id']}</td>
                                        <td class='py-2 px-4 border'>{$row['name']}</td>
                                        <td class='py-2 px-4 border'>
                                            <button class='text-blue-500 hover:underline'>Edit</button>
                                            <button class='text-red-500 hover:underline'>Delete</button>
                                        </td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                
                <div id="services" class="tab-content p-6 hidden">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
                        <input type="text" id="serv-search" class="w-full md:w-1/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search by name...">
                        <div class="flex gap-4">
                            <select id="lang-filter" class="p-2 border rounded-lg">
                                <option value="">All Languages</option>
                                <option value="rw">Kinyarwanda</option>
                                <option value="en">English</option>
                                <option value="fr">French</option>
                            </select>
                            <select id="inst-filter" class="p-2 border rounded-lg">
                                <option value="">All Institutions</option>
                                <?php
                                $inst_query = "SELECT * FROM institutions";
                                $inst_result = mysqli_query($conn, $inst_query);
                                while ($inst = mysqli_fetch_assoc($inst_result)) {
                                    echo "<option value='{$inst['id']}'>{$inst['name']}</option>";
                                }
                                ?>
                            </select>
                            <button id="add-serv-btn" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">Add Service</button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border">
                            <thead>
                                <tr class="bg-blue-100">
                                    <th class="py-2 px-4 border">ID</th>
                                    <th class="py-2 px-4 border">Institution</th>
                                    <th class="py-2 px-4 border">Service Name (RW)</th>
                                    <th class="py-2 px-4 border">Service Name (EN)</th>
                                    <th class="py-2 px-4 border">Service Name (FR)</th>
                                    <th class="py-2 px-4 border">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="serv-table-body">
                                <?php
                                $query = "SELECT s.*, i.name as inst_name FROM services s JOIN institutions i ON s.institution_id = i.id";
                                $result = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>
                                        <td class='py-2 px-4 border'>{$row['id']}</td>
                                        <td class='py-2 px-4 border'>{$row['inst_name']}</td>
                                        <td class='py-2 px-4 border'>{$row['name_rw']}</td>
                                        <td class='py-2 px-4 border'>{$row['name_en']}</td>
                                        <td class='py-2 px-4 border'>{$row['name_fr']}</td>
                                        <td class='py-2 px-4 border'>
                                            <button class='text-blue-500 hover:underline'>Edit</button>
                                            <button class='text-red-500 hover:underline'>Delete</button>
                                        </td>
                                    </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div id="inst-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h2 class="text-lg font-semibold mb-4">Add Institution</h2>
            <form id="inst-form">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Institution Name</label>
                    <input type="text" name="name" class="w-full p-2 border rounded-lg" required>
                </div>
                <div class="flex justify-end gap-4">
                    <button type="button" id="cancel-inst" class="px-4 py-2 bg-gray-300 rounded-lg">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg">Save</button>
                </div>
            </form>
        </div>
    </div>

    
    <div id="serv-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg w-full max-w-md">
            <h2 class="text-lg font-semibold mb-4">Add Service</h2>
            <form id="serv-form">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Institution</label>
                    <select name="institution_id" class="w-full p-2 border rounded-lg" required>
                        <option value="">Select Institution</option>
                        <?php
                        $inst_query = "SELECT * FROM institutions";
                        $inst_result = mysqli_query($conn, $inst_query);
                        while ($inst = mysqli_fetch_assoc($inst_result)) {
                            echo "<option value='{$inst['id']}'>{$inst['name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Service Name (Kinyarwanda)</label>
                    <input type="text" name="name_rw" class="w-full p-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Service Name (English)</label>
                    <input type="text" name="name_en" class="w-full p-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Service Name (French)</label>
                    <input type="text" name="name_fr" class="w-full p-2 border rounded-lg" required>
                </div>
                <div class="flex justify-end gap-4">
                    <button type="button" id="cancel-serv" class="px-4 py-2 bg-gray-300 rounded-lg">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Tab Switching
        $('.tab-button').click(function() {
            $('.tab-button').removeClass('border-green-500 text-gray-700 active').addClass('border-transparent text-gray-500');
            $(this).addClass('border-green-500 text-gray-700 active');
            $('.tab-content').addClass('hidden');
            $('#' + $(this).data('tab')).removeClass('hidden');
        });

        // Open Modals
        $('#add-inst-btn').click(() => $('#inst-modal').removeClass('hidden'));
        $('#add-serv-btn').click(() => $('#serv-modal').removeClass('hidden'));

        // Close Modals
        $('#cancel-inst, #cancel-serv').click(function() {
            $(this).closest('.fixed').addClass('hidden');
        });

        // Institution Form Submission
        $('#inst-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: 'save_institution.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#inst-modal').addClass('hidden');
                    location.reload();
                }
            });
        });

        // Service Form Submission
        $('#serv-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: 'save_service.php',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#serv-modal').addClass('hidden');
                    location.reload();
                }
            });
        });

        // Institution Search
        $('#inst-search').on('input', function() {
            let value = $(this).val().toLowerCase();
            $('#inst-table-body tr').filter(function() {
                $(this).toggle($(this).find('td:eq(1)').text().toLowerCase().indexOf(value) > -1);
            });
        });

        // Service Search and Filters
        $('#serv-search, #lang-filter, #inst-filter').on('input change', function() {
            let search = $('#serv-search').val().toLowerCase();
            let lang = $('#lang-filter').val();
            let inst = $('#inst-filter').val();

            $('#serv-table-body tr').filter(function() {
                let row = $(this);
                let nameMatch = row.find('td:eq(2),td:eq(3),td:eq(4)').text().toLowerCase().indexOf(search) > -1;
                let instMatch = inst ? row.find('td:eq(1)').text() === $('#inst-filter option:selected').text() : true;
                let langMatch = lang ? row.find(`td:eq(${lang === 'rw' ? 2 : lang === 'en' ? 3 : 4})`).text().toLowerCase().indexOf(search) > -1 : true;
                $(this).toggle(nameMatch && instMatch && langMatch);
            });
        });
    </script>
</body>

</html>