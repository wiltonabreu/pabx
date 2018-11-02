<?php
require_once("config.php");
//Carrega uma lista oportunidades com sales_stage = "Closed Won"

$lista = Consultas::getList();


$ramal = array();
$nome = array();
$tempo = array();
$hora = array();

$i=0;



foreach ($lista as $result) {
   $tempo[$i] = ($result['tempoligacao']);
   $horas = explode(" ", $result['data']);
   $hora[$i] = $horas[1];
   $nome[$i] = $result['nome'];
   $i++; 
}

?>

<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      
      google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawMultSeries);

function drawMultSeries() {
      var data = new google.visualization.DataTable();
      data.addColumn('timeofday', 'Time of Day');
      data.addColumn('number', 'Motivation Level');
      data.addRows([

      	<?php

				$k = $i;

				for ($i=0; $i < $k ; $i++) { 
				?>	
			     [{v: ['<?php echo $tempo[$i]?>']}, 1],
			//[{v: [8, 0, 0], f: '8 am'}, 1, .25],
				<?php
				} 
			  ?>
	          	
        ]);

        
        

      var options = {
        title: 'Motivation and Energy Level Throughout the Day',
        hAxis: {
          title: 'Time of Day',
          format: 'h:mm a',
          viewWindow: {
            min: [7, 30, 0],
            max: [17, 30, 0]
          }
        },
        vAxis: {
          title: 'Rating (scale of 1-10)'
        }
      };

      var chart = new google.visualization.ColumnChart(
        document.getElementById('chart_div'));

      chart.draw(data, options);
    }
      	 
        
                  
    </script>
  </head>
  <body>
    
    <div id="chart_div" style="width: 800px; height: 500px;"></div>
  </body>
</html>

        

        
    