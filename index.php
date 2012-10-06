<!DOCTYPE html>
<html>
<head>
	<title>TINF - Text entropy</title>
	<meta charset="utf-8">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">
		google.load("visualization", "1", {packages:["corechart"]});
		
		function drawChart(data) {
			var data = google.visualization.arrayToDataTable(data);
        	var options = {
				title: 'Letters distribution',
				hAxis: {title: 'Letter', titleTextStyle: {color: 'blue'}},
				vAxis: {title: 'Frequency', titleTextStyle: {color: 'blue'}},
			};
			var chart = new google.visualization.ColumnChart(document.getElementById('chart'));
			chart.draw(data, options);
		}
    </script>
    <style type="text/css">
    	#content {
    		margin-top: 40px;
    	}

    	h3 span {
    		color: red;
    	}

    	.browse {
    		position: relative;
    	}

    	.browse input {
    		position: absolute;
    		top: 0;
    		left: 0;
    		opacity: 0;
    	}
    </style>

</head>
<body>

	<div class="container">


		<h1>Calculating entropy of text (Croatian/English)</h1>

		<div id="content">
			<div class="row">
				<form method="POST" action="calculate.php" class="form">
					<div class="span6">
						<textarea placeholder="Paste your text here or browse .txt file" class="span6" rows="10" name="text"></textarea>
					</div>
					<div class="span6">					

						<legend>Language</legend>
						<div class="lang">
							<label class="radio">
	  							<input type="radio" name="lang" id="lang1" value="hr" checked>
								Hrvatski
							</label>
							<label class="radio">
	  							<input type="radio" name="lang" id="lang2" value="en">
	  							English
							</label>
						</div>

						<button class="btn clear">Clear</button>
						<label class="btn btn-primary browse">
							Browse txt file
							<input type="file" name="file" id="file"/>
						</label>

					</div>
				</form>
			</div>

			<div class="row">
				<h3>Entropy: <span>0</span></h3>
				<div id="chart"></div>
			</div>
		</div>


	</div>

	<script src="http://code.jquery.com/jquery-latest.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.form.js"></script>
	<script>

		jQuery(function($){

			var queue = 0;
			var $textarea = $('textarea');
			var $form = $('form');
			var formOptions = { 
				beforeSubmit: 	function() {

					if ( $('textarea[name="text"]').val() == '' && $('input[name="file"]').val() == '' )
						return false;

				},
        		success:       	function(data, statusText, xhr, $form) {
        			$('h3 span').html(data.entropy);
					var array = Array(['Letter', 'Occurences']);
					$.each(data.occurences, function(index, value){
						array.push([index, value]);
					});
					drawChart(array);
        		}
			}; 

			$('textarea').on('keyup', function(e){
				queue++;
				setTimeout(function(){
					queue--;
					if (queue <= 0) {
						queue = 0;
						$form.submit();
					}
				}, 400);
			});

			$('input').on('change', function(ev){
				$form.submit();
			});

			$('.clear').on('click', function(e){
				e.preventDefault();
				$textarea.val('');
				$('input[type=file]').val('');
			});

    		$form.ajaxForm(formOptions);

		});

	</script>
</body>
</html>