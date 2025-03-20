<?php
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Se l'utente è amministratore, può vedere tutte le immagini
$is_admin = ($_SESSION['role'] == 'admin');

if ($is_admin) {
    $stmt = $pdo->query("SELECT * FROM images");
} else {
    $stmt = $pdo->query("SELECT * FROM images WHERE inappropriate = 0");
}

$images = $stmt->fetchAll();
?>

<!-- Add Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<h1 class="text-3xl font-bold mb-6 text-center">Catalogo Immagini</h1>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    <?php foreach ($images as $image): ?>
        <div class="bg-white rounded-lg shadow-md p-4">
            <img src="<?php echo $image['filename']; ?>" alt="Immagine" class="w-full h-48 object-cover rounded" />
            <div class="mt-3">
                <?php if ($is_admin): ?>
                    <form method="post" action="inappropriate.php" class="mb-3">
                        <input type="hidden" name="image_id" value="<?php echo $image['id']; ?>" />
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-1 px-3 rounded text-sm">Segnala come Inappropriata</button>
                    </form>
                <?php endif; ?>
                <?php if (!empty($image['description'])): ?>
                    <p class="font-bold">Gemini Caption:</p>
                    <p class="italic"><?php echo $image['description']; ?></p>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>