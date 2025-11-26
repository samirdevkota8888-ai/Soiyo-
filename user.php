<?php require_once 'common/header.php'; ?>
<h1 class="text-2xl font-bold mb-6">Registered Users</h1>
<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="p-3">ID</th>
                <th class="p-3">Name</th>
                <th class="p-3">Email</th>
                <th class="p-3">Phone</th>
                <th class="p-3">Joined</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            <?php 
            $res = $conn->query("SELECT * FROM users ORDER BY id DESC");
            while($row = $res->fetch_assoc()): 
            ?>
            <tr>
                <td class="p-3"><?= $row['id'] ?></td>
                <td class="p-3 font-medium"><?= $row['name'] ?></td>
                <td class="p-3"><?= $row['email'] ?></td>
                <td class="p-3"><?= $row['phone'] ?></td>
                <td class="p-3 text-sm text-gray-500"><?= date('d M Y', strtotime($row['created_at'])) ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<?php require_once 'common/bottom.php'; ?>