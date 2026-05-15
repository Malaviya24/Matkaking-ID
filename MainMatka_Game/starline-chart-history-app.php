<?php
require_once __DIR__ . '/include/session-bootstrap.php';
include("include/connect.php");
include("include/functions.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Starline Chart History - <?php echo $site_title;?></title>

<?php include("include/head.php"); ?>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #f4f6f9;
}

/* Card Style */
.chart-card {
    background: #fff;
    border-radius: 12px;
    padding: 15px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

/* Table */
.starline-chart-table {
    font-size: 13px;
    text-align: center;
    white-space: nowrap;
}

.starline-chart-table th {
    background: #f44646;
    color: #fff;
    font-weight: 600;
    padding: 10px 6px;
    font-size: 12px;
    position: sticky;
    top: 0;
    z-index: 2;
}

.starline-chart-table td {
    padding: 8px 5px;
    vertical-align: middle;
}

/* Date column */
.starline-chart-table td.f {
    font-weight: bold;
    color: #333;
    font-size: 12px;
}

/* Number styling */
.number-top {
    font-size: 14px;
    font-weight: 600;
    color: #000;
}

.number-bottom {
    font-size: 16px;
    font-weight: bold;
    color: #f44646;
}

/* Mobile improvements */
@media (max-width: 768px) {
    .starline-chart-table {
        font-size: 11px;
    }

    .number-top {
        font-size: 12px;
    }

    .number-bottom {
        font-size: 14px;
    }
}
</style>

</head>

<body>

<div class="container mt-3 mb-5">

    <div class="text-center mb-3">
        <h4 class="fw-bold">Starline Chart History</h4>
        <small class="text-muted">Milan Starline Panel Chart</small>
    </div>

    <div class="chart-card">

        <!-- Scroll Wrapper -->
        <div class="table-responsive">

            <table class="table table-bordered starline-chart-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>09 AM</th>
                        <th>10 AM</th>
                        <th>11 AM</th>
                        <th>12 PM</th>
                        <th>01 PM</th>
                        <th>02 PM</th>
                        <th>03 PM</th>
                        <th>04 PM</th>
                        <th>05 PM</th>
                        <th>06 PM</th>
                        <th>07 PM</th>
                        <th>08 PM</th>
                    </tr>
                </thead>

                <tbody>
                <?php 
                $result = mysqli_query($con,"SELECT * FROM `starline_chart` WHERE date >= '2023-02-01'");

                while($objRs = mysqli_fetch_object($result)) {

                    echo '<tr>
                    <td class="f">
                        '.date('d/m/y', strtotime($objRs->date)).'<br>
                        <small>'.date('D', strtotime($objRs->date)).'</small>
                    </td>';

                    for ($i = 1; $i <= 12; $i++) {
                        $val = $objRs->{"value".$i};

                        echo '<td>
                                <div class="number-top">'.substr($val,0,3).'</div>
                                <div class="number-bottom">'.substr($val,-1).'</div>
                              </td>';
                    }

                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>

        </div>
    </div>

</div>

</body>
</html>