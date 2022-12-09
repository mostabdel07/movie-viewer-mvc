<main role="main" class="text-white text-center">
    <h1 class="text-white">Gallery</h1>
    <?php
    $image_alt_pair_array = array_map(null, $image_array, $alt_array);

    foreach ($image_alt_pair_array as [$image, $alt]) {
        switch ($alt) {
            case 'action':
                echo "<a href='./action'><img src='$image' alt='$alt' id='$alt'></a>" . PHP_EOL;
                break;
            case 'adventure':
                echo "<a href='./adventure.html'><img src='$image' alt='$alt' id='$alt'></a>" . PHP_EOL;
                break;
            case 'animation':
                echo "<a href='./animation.html'><img src='$image' alt='$alt' id='$alt'></a>" . PHP_EOL;
                break;
            case 'comedy':
                echo "<a href='./comedy.html'><img src='$image' alt='$alt' id='$alt'></a>" . PHP_EOL;
                break;
            case 'drama':
                echo "<a href='./drama.html'><img src='$image' alt='$alt' id='$alt'></a>" . PHP_EOL;
                break;
            case 'horror':
                echo "<a href='./horror.html'><img src='$image' alt='$alt' id='$alt'></a>" . PHP_EOL;
                break;
            default:
                echo "<img src='$image' alt='$alt' id='$alt'>" . PHP_EOL;
                break;
        }
    }
    ?>
</main>