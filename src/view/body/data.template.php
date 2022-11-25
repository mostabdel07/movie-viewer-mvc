
<main role="main" class="text-white">
<h1>Data Table</h1>

<div class="table-container table-responsive">
<table class="table table-striped table-dark">

     <?php
     require_once(__DIR__ . '/../viewlib.php');

     // echo View\get_html_header($movies_table->header);
     // echo View\get_html_body($movies_table->body);
     ///$movies_table->body $movies_table->body

     foreach($movies_table->body  as $data) {
      echo "<tr>";
      foreach($data  as $row) {
        echo "<td>" . $row 
        . "</td>";
      }
      echo "</tr> \n";
    }
     ?>

</table>
</div>
</main>