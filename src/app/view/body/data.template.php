 <!-- Page Header Start -->
 <div class="container-fluid page-header mb-5 wow fadeIn" data-wow-delay="0.1s">
   <div class="container text-center">
     <h1 class="display-4 text-white animated slideInDown mb-4">Data</h1>
     <nav aria-label="breadcrumb animated slideInDown">
       <ol class="breadcrumb justify-content-center mb-0">
         <li class="breadcrumb-item"><a class="text-white" href="/">Home</a></li>
         <li class="breadcrumb-item text-primary active" aria-current="page">Data</li>
       </ol>
     </nav>
   </div>
 </div>
 <!-- Page Header End -->


 <!-- Testimonial Start -->
 <div class="container-xxl py-5">
   <div class="container">
     <table class="table table-striped table-dark">

       <?php
        require_once(__DIR__ . '/../view.php');

        // echo View\get_html_header($movies_table->header);
        // echo View\get_html_body($movies_table->body);
        // $movies_table->body $movies_table->body

        foreach ($movies_table->body  as $data) {
          echo "<tr>";
          foreach ($data  as $row) {
            echo "<td>" . $row
              . "</td>";
          }
          echo "</tr> \n";
        }
        ?>

     </table>
   </div>
 </div>
 <!-- Testimonial End -->