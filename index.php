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
<?php
require_once 'utils/constants.php';
require_once 'file.php';

global $db;
$showcaseCount = $db->getOne('showcase');
$userListing = $db->get('userlisting');
$userListingImages = $db->get('userlistingphoto');
$showcaseData = [];
$categories = [
    ["key" => "platinum", "rows" => 2, 'cols' => $showcaseCount['platinum'] ?? 0],
    ["key" => "gold", "rows" => 3, 'cols' => $showcaseCount['gold'] ?? 0],
    ["key" => "silver", "rows" => 4, 'cols' => $showcaseCount['silver'] ?? 0]
];
for ($index = 0; $index < count($userListing); $index++) {
    $data = $userListing[$index];
    if (!empty($data)) {
        $images = array_values(array_filter($userListingImages, fn($img) => $img['userId'] === $data['userId'] && $img['showcaseIndex'] == $data['showcaseIndex']));
        if (!empty($images)) {
            foreach ($images as $image) {
                $imagePath = API_URL . '/images/' . $image['path'];
                $encryptedPath = encryptPath($imagePath);
                $data['images'][] = "file.php?img=$encryptedPath";
            }
        }
    }
    $showcaseData[] = $data;
}


?>
<nav>
    <h1>Başlık</h1>
</nav>
<main>
    <?php $showcaseIndex = 0 ?>
    <?php foreach ($categories as $category): ?>
        <section class="<?= $category['key'] ?>">
            <?php for ($i = 0; $i < $category['cols']; $i++): ?>
                <div>
                    <?php for ($j = 0; $j < $category['rows']; $j++): ?>
                        <?php
                        $showcaseIndex++;
                        $item = current(array_filter($showcaseData, fn($item) => $item['showcaseIndex'] == $showcaseIndex)) ?: [];
                        ?>
                        <div class="listing-item">
                            <a class="listing-link" href="<?= !empty($item) ? "detay.php?id=$showcaseIndex" : "#" ?>">
                                <h2 class="listing-title">
                                    <?= !empty($item) ? htmlspecialchars($item['title'] ?? 'Veri Yok') : 'İlan Yok' ?>
                                </h2>
                                <?php if (!empty($item['images'])): ?>
                                    <div class="images">
                                        <?php foreach ($item['images'] as $image): ?>
                                            <img src="<?= $image ?>" alt="<?= $item['title'] ?>">
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </a>
                            <?php if (!empty($item)): ?>
                                <a href="https://wa.me/<?= $item['phone'] ?>" target="_blank" class="whatsapp-contact">
                                    <img src="assets/whatsapp.png" alt="whatsapp">
                                    <span><?= $item['phone'] ?></span>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php endfor; ?>
        </section>
    <?php endforeach; ?>
</main>
<footer>
    <p>&copy; 2025 Tüm Hakları Saklıdır.</p>
</footer>
</body>
</html>
