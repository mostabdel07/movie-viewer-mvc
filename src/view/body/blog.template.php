

<main role="main" class="text-white text-center px-4">
<h1 class="text-white">Blog</h1> 
    <?php
        for($i = 0; $i < count($blog_entry); $i++) {
          echo "<div class='border py-2'>";
            echo "<h3>{$blog_entry[$i]['title']}</h3>". PHP_EOL;
            echo "<hr class='my-4'>";
            echo "<p>{$blog_entry[$i]['text']}</p>". PHP_EOL;
            echo "<br>";
          echo "</div>";
          }
    ?>
   </main>
