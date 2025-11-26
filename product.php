<?php require_once 'common/header.php'; 

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    if(isset($_POST['delete_id'])){
        $conn->query("DELETE FROM products WHERE id={$_POST['delete_id']}");
        exit;
    }
    
    $name = $_POST['name'];
    $cat = $_POST['cat_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $desc = $conn->real_escape_string($_POST['description']);

    $imgSql = "";
    if(isset($_FILES['image']) && $_FILES['image']['size'] > 0){
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $fname = "prod_" . time() . "." . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/" . $fname);
        $imgSql = ", image='$fname'";
    }

    if(!empty($_POST['id'])){
        $id = $_POST['id'];
        $sql = "UPDATE products SET name='$name', cat_id='$cat', price='$price', stock='$stock', description='$desc' $imgSql WHERE id=$id";
    } else {
        $fname = isset($fname) ? $fname : '';
        $sql = "INSERT INTO products (name, cat_id, price, stock, description, image) VALUES ('$name', '$cat', '$price', '$stock', '$desc', '$fname')";
    }
    $conn->query($sql);
    exit;
}

$cats = $conn->query("SELECT * FROM categories");
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Products</h1>
    <button onclick="openModal()" class="bg-blue-600 text-white px-4 py-2 rounded shadow">+ Add New</button>
</div>

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="w-full text-left whitespace-nowrap">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="p-3">ID</th>
                <th class="p-3">Image</th>
                <th class="p-3">Name</th>
                <th class="p-3">Price</th>
                <th class="p-3">Stock</th>
                <th class="p-3">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y">
            <?php 
            $res = $conn->query("SELECT * FROM products ORDER BY id DESC");
            while($row = $res->fetch_assoc()): 
            ?>
            <tr>
                <td class="p-3"><?= $row['id'] ?></td>
                <td class="p-3">
                    <?php if($row['image']): ?><img src="../uploads/<?= $row['image'] ?>" class="w-10 h-10 object-cover rounded"><?php endif; ?>
                </td>
                <td class="p-3 font-medium"><?= $row['name'] ?></td>
                <td class="p-3">₹<?= $row['price'] ?></td>
                <td class="p-3"><?= $row['stock'] ?></td>
                <td class="p-3">
                    <button onclick='openModal(<?= json_encode($row) ?>)' class="text-blue-500 mr-2"><i class="fas fa-edit"></i></button>
                    <button onclick="delProd(<?= $row['id'] ?>)" class="text-red-500"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <form onsubmit="saveProd(event)" class="bg-white rounded-lg p-6 w-full max-w-lg shadow-xl h-[90vh] overflow-y-auto">
        <h2 class="text-xl font-bold mb-4">Product Details</h2>
        <input type="hidden" name="id" id="pid">
        <div class="grid grid-cols-2 gap-4">
            <div class="mb-3 col-span-2">
                <label class="block text-xs font-bold mb-1">Name</label>
                <input type="text" name="name" id="pname" required class="w-full border p-2 rounded">
            </div>
            <div class="mb-3">
                <label class="block text-xs font-bold mb-1">Category</label>
                <select name="cat_id" id="pcat" class="w-full border p-2 rounded">
                    <?php while($c = $cats->fetch_assoc()): ?>
                        <option value="<?= $c['id'] ?>"><?= $c['name'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="block text-xs font-bold mb-1">Price</label>
                <input type="number" name="price" id="pprice" required class="w-full border p-2 rounded">
            </div>
            <div class="mb-3">
                <label class="block text-xs font-bold mb-1">Stock</label>
                <input type="number" name="stock" id="pstock" required class="w-full border p-2 rounded">
            </div>
            <div class="mb-3">
                <label class="block text-xs font-bold mb-1">Image</label>
                <input type="file" name="image" class="w-full border p-1 rounded">
            </div>
            <div class="mb-3 col-span-2">
                <label class="block text-xs font-bold mb-1">Description</label>
                <textarea name="description" id="pdesc" class="w-full border p-2 rounded h-24"></textarea>
            </div>
        </div>
        <div class="flex justify-end gap-2 mt-4">
            <button type="button" onclick="document.getElementById('modal').classList.add('hidden')" class="bg-gray-200 px-4 py-2 rounded">Cancel</button>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
        </div>
    </form>
</div>

<script>
    function openModal(d = null) {
        document.getElementById('modal').classList.remove('hidden');
        document.getElementById('pid').value = d ? d.id : '';
        document.getElementById('pname').value = d ? d.name : '';
        document.getElementById('pprice').value = d ? d.price : '';
        document.getElementById('pstock').value = d ? d.stock : '';
        document.getElementById('pdesc').value = d ? d.description : '';
        if(d) document.getElementById('pcat').value = d.cat_id;
    }

    async function saveProd(e) {
        e.preventDefault();
        const fd = new FormData(e.target);
        await fetch('', { method: 'POST', body: fd });
        location.reload();
    }
    
    async function delProd(id) {
        if(!confirm('Delete?')) return;
        const fd = new FormData(); fd.append('delete_id', id);
        await fetch('', { method: 'POST', body: fd });
        location.reload();
    }
</script>
<?php require_once 'common/bottom.php'; ?>