<?php require_once 'common/header.php'; ?>
<h1 class="text-2xl font-bold mb-6">Orders</h1>
<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-left whitespace-nowrap">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="p-3">ID</th>
                <th class="p-3">Customer</th>
                <th class="p-3">Total</th>
                <th class="p-3">Status</th>
                <th class="p-3">Date</th>
                <th class="p-3">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            <?php 
            $res = $conn->query("SELECT * FROM orders ORDER BY id DESC");
            while($row = $res->fetch_assoc()): 
                $color = 'text-orange-500';
                if($row['status']=='Delivered') $color='text-green-600';
                if($row['status']=='Cancelled') $color='text-red-600';
            ?>
            <tr>
                <td class="p-3">#<?= $row['id'] ?></td>
                <td class="p-3"><?= $row['name'] ?></td>
                <td class="p-3">₹<?= $row['total_amount'] ?></td>
                <td class="p-3 font-bold <?= $color ?>"><?= $row['status'] ?></td>
                <td class="p-3 text-sm text-gray-500"><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                <td class="p-3">
                    <a href="order_detail.php?id=<?= $row['id'] ?>" class="bg-blue-100 text-blue-600 px-3 py-1 rounded text-xs font-bold">View</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php require_once 'common/bottom.php'; ?>