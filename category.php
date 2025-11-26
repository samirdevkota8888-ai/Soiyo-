<?php require_once 'common/header.php'; 

// AJAX Handlers
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name = $_POST['name'];
    
    if(isset($_POST['delete_id'])){
        $conn->query("DELETE FROM categories WHERE id={$_POST['delete_id']}");
        exit;
    }

    $imgSql = "";
    if(isset($_FILES['image']) && $_FILES['image']['size'] > 0){
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $fname = "cat_" . time() . "." . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $fname);
        $imgSql = ", image='$fname'";
    }

    if(!empty($_POST['id'])){
        $id = $_POST['id'];
        $sql = "UPDATE categories SET name='$name' $imgSql WHERE id=$id";
    } else {
        $fname = isset($fname) ? $fname : '';
        $sql = "INSERT INTO categories (name, image) VALUES ('$name', '$fname')";
    }
    $conn->query($sql);
    exit;
}
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Categories</h1>
    <button onclick="openModal()" class="bg-blue-600 text-white px-4 py-2 rounded shadow">+ Add New</button>
</div>

<div class="bg-white rounded shadow overflow-hidden">
    <table class="w-full text-left">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="p-3">ID</th>
                <th class="p-3">Image</th>
                <th class="p-3">Name</th>
                <th class="p-3">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            <?php 
            $res = $conn->query("SELECT * FROM categories ORDER BY id DESC");
            while($row = $res->fetch_assoc()): 
            ?>
            <tr>
                <td class="p-3"><?= $row['id'] ?></td>
                <td class="p-3">
                    <?php if($row['image']): ?>
                        <img src="../uploads/<?= $row['image'] ?>" class="w-10 h-10 object-cover rounded">
                    <?php endif; ?>
                </td>
                <td class="p-3 font-semibold"><?= $row['name'] ?></td>
                <td class="p-3">
                    <button onclick='openModal(<?= json_encode($row) ?>)' class="text-blue-500 mr-2"><i class="fas fa-edit"></i></button>
                    <button onclick="delCat(<?= $row['id'] ?>)" class="text-red-500"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <form id="catForm" onsubmit="saveCat(event)" class="bg-white rounded-lg p-6 w-full max-w-md shadow-xl">
        <h2 id="modalTitle" class="text-xl font-bold mb-4">Add Category</h2>
        <input type="hidden" name="id" id="catId">
        <div class="mb-4">
            <label class="block text-sm font-bold mb-1">Name</label>
            <input type="text" name="name" id="catName" required class="w-full border p-2 rounded">
        </div>
        <div class="mb-4">
            <label class="block text-sm font-bold mb-1">Image</label>
            <input type="file" name="image" class="w-full border p-1 rounded text-sm">
        </div>
        <div class="flex justify-end gap-2">
            <button type="button" onclick="document.getElementById('modal').classList.add('hidden')" class="bg-gray-200 px-4 py-2 rounded">Cancel</button>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
        </div>
    </form>
</div>

<script>
    function openModal(data = null) {
        document.getElementById('modal').classList.remove('hidden');
        document.getElementById('catId').value = data ? data.id : '';
        document.getElementById('catName').value = data ? data.name : '';
        document.getElementById('modalTitle').innerText = data ? 'Edit Category' : 'Add Category';
    }

    async function saveCat(e) {
        e.preventDefault();
        const fd = new FormData(e.target);
        await fetch('', { method: 'POST', body: fd });
        location.reload();
    }

    async function delCat(id) {
        if(!confirm('Delete this category?')) return;
        const fd = new FormData();
        fd.append('delete_id', id);
        await fetch('', { method: 'POST', body: fd });
        location.reload();
    }
</script>
<?php require_once 'common/bottom.php'; ?>