<?php require_once 'common/header.php'; ?>
<h1 class="text-2xl font-bold mb-6">Dashboard</h1>
<div class="grid grid-cols-2 md:grid-cols-3 gap-4">
    <?php
    $stats = [
        ['Users', 'users', 'fa-users', 'bg-blue-100 text-blue-600'],
        ['Orders', 'orders', 'fa-shopping-cart', 'bg-purple-100 text-purple-600'],
        ['Revenue', 'orders', 'fa-rupee-sign', 'bg-green-100 text-green-600', 'SUM(total_amount)'],
        ['Products', 'products', 'fa-box', 'bg-orange-100 text-orange-600'],
        ['Categories', 'categories', 'fa-list', 'bg-teal-100 text-teal-600'],
        ['Pending', 'orders', 'fa-clock', 'bg-yellow-100 text-yellow-600', 'COUNT(*)', "WHERE status='Placed'"]
    ];
    
    foreach($stats as $s):
        $sel = isset($s[4]) ? $s[4] : 'COUNT(*)';
        $whr = isset($s[5]) ? $s[5] : '';
        $val = $conn->query("SELECT $sel as v FROM {$s[1]} $whr")->fetch_assoc()['v'];
        $val = $val ? number_format($val) : 0;
    ?>
    <div class="bg-white p-4 rounded-lg shadow flex items-center gap-4">
        <div class="w-12 h-12 rounded-full flex items-center justify-center text-xl <?= $s[3] ?>">
            <i class="fas <?= $s[2] ?>"></i>
        </div>
        <div>
            <p class="text-gray-500 text-xs font-bold uppercase"><?= $s[0] ?></p>
            <p class="text-xl font-bold text-gray-800"><?= $val ?></p>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php require_once 'common/bottom.php'; ?>