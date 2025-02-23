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
    <script defer src="script.js"></script>

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
$showcaseIndex = 1;
$categories = [
    ["key" => "platinum", "columns" => 2],
    ["key" => "gold", "columns" => 3],
    ["key" => "silver", "columns" => 5]
];

foreach ($categories as $category) {
    $key = $category['key'];
    for ($i = 0; $i < $showcaseCount[$key]; $i++, $showcaseIndex++) {
        $data = current(array_filter($userListing, fn($item) => $item['showcaseIndex'] == $showcaseIndex)) ?: [];
        if (!empty($data)) {
            $images = array_values(array_filter($userListingImages, fn($img) => $img['userId'] == ($data['userId'] ?? null) && $img['showcaseIndex'] == $showcaseIndex));
            if (!empty($images)) {
                foreach ($images as $image) {
                    $imagePath = API_URL . '/images/' . $image['path'];
                    $encryptedPath = encryptPath($imagePath);
                    $data['images'][] = "file.php?img=$encryptedPath";
                }
            }
        }

        $showcaseData[$key][] = $data;;
    }
}
?>
<nav>
    <h1>Başlık</h1>
</nav>
<main>
    <?php foreach ($categories as $category): ?>
        <?php if (!empty($showcaseData[$category['key']])): ?>
            <section class="<?= $category['key'] ?>">
                <?php foreach ($showcaseData[$category['key']] as $item): ?>
                    <div>
                        <?php for ($i = 0; $i < $category['columns']; $i++): ?>
                            <div class="listing-item">
                                <h2>
                                    <?= !empty($item) ? htmlspecialchars($item['title'] ?? 'Veri Yok') : 'İlan Yok' ?>
                                </h2>
                                <?php if (!empty($item['images'])): ?>
                                    <div class="images">
                                        <?php foreach ($item['images'] as $image): ?>
                                            <img src="<?= $image ?>" alt="<?= $item['title'] ?>">
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($item)): ?>
                                    <p>Telefon: <?= $item['phone'] ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endfor; ?>
                    </div>
                <?php endforeach; ?>
            </section>
        <?php endif; ?>
    <?php endforeach; ?>
</main>
<footer>
    <p>&copy; 2025 Tüm Hakları Saklıdır.</p>
</footer>
</body>
</html>
