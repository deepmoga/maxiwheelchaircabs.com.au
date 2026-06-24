<?php
$images = [
    'hero-bg.jpg' => [1920, 1080, [30, 30, 50], 'Hero Background'],
    'hero-car.png' => [600, 400, [50, 50, 70], 'Hero Car'],
    'about-main.jpg' => [600, 400, [40, 40, 60], 'About Main'],
    'about-small.jpg' => [300, 300, [60, 60, 80], 'About Small'],
    'service-wheelchair.jpg' => [400, 300, [45, 45, 65], 'Wheelchair Taxi'],
    'service-airport.jpg' => [400, 300, [35, 50, 65], 'Airport Transfer'],
    'service-babyseat.jpg' => [400, 300, [50, 45, 60], 'Baby Seat Taxi'],
    'service-wedding.jpg' => [400, 300, [55, 50, 70], 'Wedding Transport'],
    'why-choose.jpg' => [600, 500, [40, 45, 55], 'Why Choose Us'],
];

foreach ($images as $filename => $config) {
    list($w, $h, $rgb, $label) = $config;

    $img = imagecreatetruecolor($w, $h);
    $bg = imagecolorallocate($img, $rgb[0], $rgb[1], $rgb[2]);
    imagefill($img, 0, 0, $bg);

    $yellow = imagecolorallocate($img, 255, 193, 7);
    $white = imagecolorallocate($img, 200, 200, 200);

    imagestring($img, 5, ($w / 2) - (strlen($label) * 4.5), ($h / 2) - 10, $label, $yellow);
    imagestring($img, 3, ($w / 2) - (strlen("{$w}x{$h}") * 3.5), ($h / 2) + 15, "{$w}x{$h}", $white);

    if (str_ends_with($filename, '.png')) {
        imagepng($img, __DIR__ . '/' . $filename);
    } else {
        imagejpeg($img, __DIR__ . '/' . $filename, 85);
    }

    imagedestroy($img);
    echo "Created: {$filename}\n";
}

// Create a simple logo
$logo = imagecreatetruecolor(250, 60);
imagesavealpha($logo, true);
$transparent = imagecolorallocatealpha($logo, 0, 0, 0, 127);
imagefill($logo, 0, 0, $transparent);
$dark = imagecolorallocate($logo, 26, 26, 46);
$gold = imagecolorallocate($logo, 255, 193, 7);
imagestring($logo, 5, 10, 10, 'MAXI', $dark);
imagestring($logo, 5, 55, 10, 'WHEELCHAIR', $gold);
imagestring($logo, 4, 10, 30, 'CABS PERTH', $dark);
imagepng($logo, __DIR__ . '/logo.png');
imagedestroy($logo);
echo "Created: logo.png\n";

echo "\nAll placeholder images generated successfully!\n";
?>
