<h1>Welcome!</h1>

<div class="row">
	<div class="col-md-6">
		<h3>Daily Sales</h3>
		<canvas id="chtDaily"></canvas>
	</div>

	<div class="col-md-6">
		<h3>Monthly Sales</h3>
		<canvas id="chtMonthly"></canvas>
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<h3>Yearly Sales</h3>
		<canvas id="chtYearly"></canvas>
	</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script type="text/javascript">

	function getPreviousDaysList() {
		var result = [];
		for(var i = 0; i < 15; i++) {
			var lastDay = moment().subtract(i, 'days');
			var display = lastDay.format('MMM D');
			var dateQuery = lastDay.format('YYYY-MM-DD');
			result.push({
				display: display,
				dateQuery: dateQuery,
			});
		}
		return result.reverse();
	}

	function getPreviousMonthsList() {
		var result = [];
		for(var i = 0; i < 6; i++) {
			var lastDay = moment().subtract(i, 'months');
			var display = lastDay.format('MMM');
			var startOfMonth = lastDay.clone().startOf('month').format('YYYY-MM-DD');
			var endOfMonth = lastDay.clone().endOf('month').format('YYYY-MM-DD');
			var dateQuery = {
				start: startOfMonth,
				end: endOfMonth
			};
			result.push({
				display: display,
				dateQuery: dateQuery,
			});
		}
		return result.reverse();
	}

	function getPreviousYearsList() {
		var result = [];
		for(var i = 0; i < 5; i++) {
			var lastDay = moment().subtract(i, 'years');
			var display = lastDay.format('YYYY');
			result.push({
				display: display,
				dateQuery: display,
			});
		}
		return result.reverse();
	}

	function loadChart(type) {

		var daysDateList = [];
		var url = '';
		var chartID = '';
		switch (type) {
			case 'monthly':
				daysDateList = getPreviousMonthsList();
				url = BASE_URL + 'invoice/get_monthly_sales';
				chartID = 'chtMonthly';
			break;
			case 'yearly':
				daysDateList = getPreviousYearsList();
				url = BASE_URL + 'invoice/get_yearly_sales';
				chartID = 'chtYearly';
			break;
			default:
				daysDateList = getPreviousDaysList();
				url = BASE_URL + 'invoice/get_daily_sales';
				chartID = 'chtDaily';
			break;
		}
		var labels = [];
		var dateQueryList = [];
		for (i in daysDateList) {
			labels.push(daysDateList[i].display);
			dateQueryList.push(daysDateList[i].dateQuery);
		}
		
		$.ajax({
			url: url,
			type: "post",
			data: { date_list: dateQueryList },
			success: function(datasetData){
				var data = {
					labels: labels,
					datasets: [{
						label: 'Sales',
						backgroundColor: 'rgb(255, 99, 132)',
						borderColor: 'rgb(255, 99, 132)',
						data: datasetData
					}]
				};

				var config = {
					type: 'line',
					data,
					options: {}
				};
				var myChart = new Chart(
					document.getElementById(chartID),
					config
				);
			},
			error: function(error){
				
				alert(error.responseText);
			}
		});
	}

	$(document).ready(function() {

		loadChart('daily');
		loadChart('monthly');
		loadChart('yearly');
	});

</script>
