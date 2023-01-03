<?php 
    /*
 * Template name: Мой Супер-шаблон
 * Template post type: poll
 */
?>
<?php 
    get_header(); 
    $count_array = get_post_meta( get_the_ID(), 'count', true);
?>

<script>
  google.charts.load('current', {packages: ['corechart']});
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    // Define the chart to be drawn.
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Element');
    data.addColumn('number', 'Percentage');
    data.addRows([
      <?php
      foreach ($count_array as $key => $value) {
            if($key == "set"){
                continue;
            }
            $percents = ($value / $count_array['set']['total']);
            echo "['$key', $percents],";
      }
      ?>
    ]);
    // Instantiate and draw the chart.
    var chart = new google.visualization.PieChart(document.getElementById('myPieChart'));
    chart.draw(data, null);
  }
</script>

<div class="container">
    <div class="diagram__wrapper">
        <div class="diagram__description">
            Вопрос: <?php the_title(); ?>
        </div>
        <div class="diagram__radial">
            <div class="diagram__radial-title"></div>
            <div class="diagram__radial-round">
            
                <div id="myPieChart" style="width:100%; height:700px"></div>
        
                <?php
                    foreach ($count_array as $key => $value) {
                        if($key == "set"){
                            continue;
                        }
                        $percents = round(($value / $count_array['set']['total']) * 100, 0);
                        echo "<div class='radial__item animate' style='--p:" . $percents . ";--c:lightgreen'>" . $percents . "% </div> " . $key . "<br>";
                      
                    }
                ?>
            </div>
        </div>
        
        <div class="diagram__statistic-table">
            <pre>
                <?php var_dump(get_post_meta( get_the_ID(), 'count', true));?>
            </pre>
        </div>
    </div>


<div class="rating">    
    <div class="rating__body">
        <div class="rating__active"></div>
        <div class="rating__active">
            <input type="radio" class="rating__item" value="1" name="rating">
            <input type="radio" class="rating__item" value="2" name="rating">
            <input type="radio" class="rating__item" value="3" name="rating">
            <input type="radio" class="rating__item" value="4" name="rating">
            <input type="radio" class="rating__item" value="5" name="rating">
        </div>
    </div>
    <div class="rating__value">5</div>
</div>

<script>
    const ratings = document.querySelectorAll('.rating');
    if(ratings.length > 0) {
    	initRatings();
    }
    
    function initRatings() {
    	let ratingActive, ratingValue;
      for (let index = 0; index < ratings.length; index++){
      	const rating = ratings[index];
        initRating(rating);
      }
  
        function initRating(rating){
        	initRatingVars(rating);

        	setRatingActiveWidth();
            
        }

        function initRatingVars(rating){
        	ratingActive = rating.querySelector('.rating__active');
        	ratingValue = rating.querySelector('.rating__value');
        }

        function setRatingActiveWidth(index = ratingValue.innerHTML){
          const ratingActiveWidth = index / 0.05;
          ratingActive.style.width = ratingActiveWidth+'%';
          
        }
    }
</script>                


</div>
<?php get_footer();?>