<?php
// save_charts.php

// Get the base64 data from POST request
$data = json_decode(file_get_contents('php://input'), true);

$histogramData = $data['histogram'];
$pieData = $data['pie'];

// Convert base64 to image and save it
function saveBase64Image($base64Data, $path) {
    $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Data));
    file_put_contents($path, $imageData);
}

// Save the images
saveBase64Image($histogramData, 'charts/histogram_chart.png');
saveBase64Image($pieData, 'charts/pie_chart.png');

// Send a success response
echo json_encode(['status' => 'success']);
?>
