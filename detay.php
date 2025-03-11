<?php
require_once 'utils/constants.php';
require_once 'file.php';

global $db;
$showcaseIndex = $_GET['id'];
$db->where('showcaseIndex', $showcaseIndex);
$showcaseData = $db->getOne('userlisting');
$data = [
    'title' => $showcaseData['title'],
    'description' => $showcaseData['description'],
    'images' => [],
    'phone' => $showcaseData['phone'],
];
$db->where('showcaseIndex', $showcaseIndex);
$images = $db->get('userlistingphoto');
for ($i = 0; $i < count($images); $i++) {
    $imagePath = API_URL . '/images/' . $images[$i]['path'];
    $encryptedPath = encryptPath($imagePath);
    $data['images'][] = "file.php?img=$encryptedPath";
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SEO uyumlu, hızlı ve mobil dostu HTML5 şablon.">
    <meta name="keywords" content="SEO, HTML5, Mobil Uyumlu, Web Geliştirme">
    <meta name="author" content="Your Name">
    <title>SEO Uyumlu Web Sitesi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
          rel="stylesheet">
    <link rel="canonical" href="https://www.siteniz.com/">
    <link rel="stylesheet" href="reset.css">
    <link rel="stylesheet" href="styles.css">

</head>
<body>
<nav>
    <h1>Başlık</h1>
</nav>
<main>
    <section class="detail">
        <h1 class="detail-title">
            <?= $data['title'] ?>
        </h1>
        <div class="detail-images">
            <?php foreach ($data['images'] as $image): ?>
                <img src="<?= $image ?>" alt="<?= $data['title'] ?>">
            <?php endforeach; ?>
        </div>
        <a href="https://wa.me/<?= $data['phone'] ?>" target="_blank" class="whatsapp-contact-detail">
            <img src="assets/whatsapp.png" alt="whatsapp">
            <span><?= $data['phone'] ?></span>
        </a>
        <p>
            <?= $data['description'] ?>
        </p>
    </section>
</main>
<footer>
    <p>&copy; 2025 Tüm Hakları Saklıdır.</p>
</footer>
</body>
</html>
