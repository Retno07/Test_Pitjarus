<!DOCTYPE html>  
<html>  
<head>  
    <title>HighChart</title>  
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js"></script>  
    <script src="https://code.highcharts.com/highcharts.js"></script>  
</head>  
<br>


<div class="container"> 
    <div class="col-md-10 col-md-offset-3">   
        <br>
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date">

        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date">

        <button class="btn btn-primary" id="view_button" onclick="OnClick()">View</button>
    </div>
</div>  

<script type="text/javascript">

        function OnClick() {
            var startDate = document.getElementById('start_date').value;
            var endDate = document.getElementById('end_date').value;

            fetchChart(startDate, endDate);
            fetchTable(startDate, endDate); 
        }
        
        document.getElementById("view_button").addEventListener("click", function () {
        OnClick();
    });

        function fetchChart(startDate, endDate) {
            $.ajax({
                url: 'http://localhost:5000/api/UpdateChart?start_date=' + startDate + '&end_date=' + endDate,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    renderChart(data.data)
                    // console.log(data);
                    // alert('success.');
                },
                error: function() {
                     $('#container').html('Terjadi kesalahan saat mengambil data chart.');
                } 
            });
        }

        function fetchTable(startDate, endDate) {
        $.ajax({
            url: 'http://localhost:5000/api/UpdateTable?start_date=' + startDate + '&end_date=' + endDate,
            type: 'GET',
            dataType: 'json',
            success: function(datas) {
                updateTable(datas);
            },
            error: function() {
                $('#table_result').html('<tr><td colspan="6">Terjadi kesalahan saat mengambil data tabel.</td></tr>');
            }
        });
    }

        function renderChart(data) {
            var totals = data.map(item => item.total); // Ambil nilai total dari setiap objek dalam array
            var chartData = totals.map(Number);  
            $('#container').highcharts({  
                chart: {  
                    type: 'column'  
                },  
                title: {  
                    text: 'Chart'  
                },  
                xAxis: {  
                    categories: ['DKI Jakarta','Jawa Barat','Kalimantan', 'Jawa Tengah', 'Bali']  
                },  
                yAxis: {  
                    title: {  
                        text: 'Percent &'  
                    },
                    min: 0,
                    max: 100  
                },  
                series: [{  
                    name: 'Nilai',  
                    data: chartData
                }]
            });
        }

        function updateTable(datas) {
            var test = datas;
            console.log(test);
        //     var tableData = data.map(function(row) {
        //     return '<tr>' +
        //         '<td>' + row.brand_name + '</td>' +
        //         '<td>' + row.jakarta + '%</td>' +
        //         '<td>' + row.jabar + '%</td>' +
        //         '<td>' + row.kalimantan + '%</td>' +
        //         '<td>' + row.jateng + '%</td>' +
        //         '<td>' + row.bali + '%</td>' +
        //         '</tr>';
        // }).join('');
        $('#table_result').append(
                        '<tr><td>' + test.brand_name +
                        // '</td><td>' + test.TransactionId +
                        // '</td><td>' + item.Amount +
                        // '</td><td>' + item.Status + 
                        '</td></tr>'
                    )
        // $('#table_result').html(test);
        // $('#table_result').html(tableData); 
    }
    </script>

<body>  
<script type="text/javascript">

$(function () {   
    
    var chart = <?php echo $chart; ?>; 
    $('#container').highcharts({  
        chart: {  
            type: 'column'  
        },  
        title: {  
            text: 'Chart'  
        },  
        xAxis: {  
            categories: ['DKI Jakarta','Jawa Barat','Kalimantan', 'Jawa Tengah', 'Bali']  
        },  
        yAxis: {  
            title: {  
                text: 'Percent &'  
            },
            min: 0,
            max: 100  
        },  
        series: [{  
            name: 'Nilai',  
            data: chart  
        }]  
    });  
});  
    
</script>  
    
<div class="container">  
    <br/>  
    <h2 class="text-center"> Highcharts in Codeigniter MYSQL JSON </h2>  
    <!-- <?php 
    echo $grid;
     ?> -->
    <div class="row">  
        <div class="col-md-10 col-md-offset-1">  
            <div class="panel panel-default">  
                <div class="panel-heading">Dashboard</div>  
                <div class="panel-body">  
                    <div id="container"></div>  
                </div>  
            </div>  
        </div>  
    </div>  
</div>  

<table style="width:950px;" class="table table-bordered" align="center">
<thead>
<tr bgcolor="skyblue">
        <th>Brand</th>
        <th>DKI Jakarta</th>
        <th>Jawa Barat</th>
        <th>Kalimantan</th>
        <th>Jawa Tengah</th>
        <th>Bali</th>
    </tr>
</thead>
<tbody>
    <?php foreach ($grid as $row) { ?>
        
        <tr>
            <td><?php echo $row->brand_name ?></td>
            <td><?php echo $row->jakarta ?>%</td>
            <td><?php echo $row->jabar ?>%</td>
            <td><?php echo $row->kalimantan ?>%</td>
            <td><?php echo $row->jateng ?>%</td>
            <td><?php echo $row->bali ?>%</td>
        </tr>
    <?php } ?>
</tbody>

<tbody id="table_result">


</tbody>

</table>

</body>  
</html> 