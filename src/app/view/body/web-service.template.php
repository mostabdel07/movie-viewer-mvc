<main role="main" class="text-white text-center">
    <h1 class="text-white">Billboard</h1>
    <div class="billboard-container">
        <?php

        foreach ($movies_array as $item) {

            if (array_key_exists('title', $item)) echo "<img src='https://image.tmdb.org/t/p/w500{$item['poster_path']}' alt='{$item['title']}'/'> <br>";
            else echo "<img src='https://image.tmdb.org/t/p/w500{$item['poster_path']}' alt='{$item['name']}'/'> <br>";
        };

        ?>
    </div>
</main>