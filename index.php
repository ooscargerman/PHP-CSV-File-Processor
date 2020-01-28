<?php include('./core/processor.php');
$Processor = new Processor();

?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="css/style.css">

    <link rel="icon" href="Favicon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

    <title>PHP Exercise – File Processor</title>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light navbar-laravel">
    <div class="container">
        <a class="navbar-brand" href="#">PHP Exercise – File Processor</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<main class="login-form">
    <div class="cotainer">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Select CSV File</div>
                    <div class="card-body">


                        <form action="#" method="post" enctype="multipart/form-data">
                            <div class="form-group row">
                                <div class=" col-md-10 custom-file">
                                    <input type="file" class="custom-file-input" name="file"
                                           aria-describedby="inputGroupFileAddon01"
                                           onchange="return onChangeFileInput(this);" id="file"/>
                                    <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                </div>
                                <div class="col-md-2">
                                    <input class="btn btn-primary" type="submit" name="submit"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<br>
<?php
if (isset($_POST["submit"])) {
    if (isset($_FILES["file"])) {
        if ($_FILES["file"]["error"] > 0) {
            echo "Return Codes: " . $_FILES["file"]["error"] . "<br />";
        } else {
            $Processor->CSV_Reader($_FILES["file"]['tmp_name'], true);
            ?>
            <main class="login-form">
                <div class="cotainer">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card-body">
                                <table class='table table-bordered table-striped'>
                                    <tr class="col-md-8">
                                    <tr>
                                        <?php
                                        $UsdPosition = $Processor->SearchPositionArray("Total Profit (USD)", $Processor->CvsContent);
                                        $QTYPosition = $Processor->SearchPositionArray("QTY", $Processor->CvsContent);
                                        $ProfitMarginPosition = $Processor->SearchPositionArray("Profit Margin", $Processor->CvsContent);
                                        array_push($Processor->CvsContent, 'Total Profit (CAD)');
                                        foreach ($Processor->CvsContent as $headercolumn) {
                                            echo "<th>$headercolumn</th>";
                                        }
                                        ?>
                                    </tr>
                                    <?php
                                    echo '<tr>';
                                    foreach ($Processor->CvsData as $number => $number_array) {
                                        $indexTemp=0;
                                        foreach ($number_array as $key => $value) {
                                            if($key == $UsdPosition){
                                                $CAD = $value;
                                                if(is_numeric($value)) {
                                                    echo '<td><span class="badge badge-'. ($value >= 0 ?  "success" :  "danger" ).'">$'.money_format('%+.2n',$value).'</td>';
                                                }else{
                                                    echo "<td>$value</td>";
                                                }
                                            }elseif ($key == $QTYPosition || $key == $ProfitMarginPosition ){
                                                echo '<td><span class="badge badge-'. ($value >= 0 ?  "success" :  "danger" ).'">'.$value.'</td>';

                                            }else{
                                                echo "<td>$value</td>";
                                            }
                                            $Processor->arrayHeaders[$Processor->arrayIndexHeaders[$indexTemp]] += $value;
                                            $indexTemp++;
                                        }
                                        echo '<td><span class="badge badge-'. ($CAD >= 0 ?  "success" :  "danger" ).'">$'.$Processor->getCAD($CAD).'</td>';
                                        echo " </tr>";

                                    }


                                    ?>

                                </table>
                                <table class='table table-bordered table-striped'>
                                    <div class="col-md-8">
                                        <tr>
                                            <th>Average Cost</th>
                                            <th>Average Price</th>
                                            <th>Total Quantity</th>
                                            <th>Average Profit Margin</th>
                                            <th>Total Profit (USD)</th>
                                            <th>Total Profit (CAD)</th>
                                        </tr>
                                        <?php
                                        foreach ($Processor->arrayHeaders as $k => $v) {
                                            if(strcasecmp($k, "Cost") == 0|| strcasecmp($k, "QTY") == 0 || strcasecmp($k, "Price") == 0
                                                || strcasecmp($k, "Profit Margin") == 0){
                                                echo "<td>$v</td>";
                                            }elseif (strcasecmp($k, "Total Profit (USD)") == 0){
                                                $USDtoCAD = $v;
                                                echo '<td><span class="badge badge-'. ($v >= 0 ?  "success" :  "danger" ).'">$'.$v.'</td>';
                                            }
                                        }
                                        echo '<td><span class="badge badge-'. ($Processor->getCAD($USDtoCAD) >= 0 ?  "success" :  "danger" ).'">$'.$Processor->getCAD($USDtoCAD).'</td>';
                                        ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </main>
            <?php
        }
    } else {
        echo "No file selected <br />";
    }
}
?>
</div>
</div>
<script src="js/input.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</body>
</html>