<html>
    <style>
        .container {
            margin: auto 100px;
        }
        .header {
            border-bottom: 1px solid black;
        }
        .content {
            margin-top: 20px;
            font-size: 20px;
        }
    </style>
    <body>
        <div class='container'>
            <div class='header'>
                <img src='logo.jpeg' width='200px'/>
            </div>
            <div class='content'>
                <b>Processes Files: </b><br><br>

<?php

require_once('pdf.php');

$sourceDir='rptfiles';
$archiveDir='archives';

if (is_dir($sourceDir)) {
    if ($dh = opendir($sourceDir)) {
        while (($file = readdir($dh)) !== false) {
            if (stripos($file, '.rpt') > 0) {
                processRPT($sourceDir, $file);
                moveFiles($file, $sourceDir, $archiveDir);
            }
        }
        closedir($dh);
    }
}

function processRPT($sourceDir, $file){
    $filePath = $sourceDir.'/'.$file;
    echo __DIR__.'/'.$filePath.'<br/>';
    $lines = explode("\r\n", file_get_contents($filePath));
    $content = processContent($lines);
    date_default_timezone_set('America/Chicago');
    $date = date('Y-m-d', filectime($filePath));
    pdfGenerate($content, $file, $date);
}

function processContent($lines) {
    $content = array_merge([], array_filter($lines));
    //print_r($content);
    $response = [];
    $first = array_merge([], array_filter(explode(' ', $content[0])));
    if (stripos($first[1], '/') > 0) {
        $response['date'] = trim($first[1].' '.$first[2]);
        $response['name'] = trim($first[0]);
    } elseif(stripos($first[2], '/') > 0) {
        $response['date'] = trim($first[2]).' '.trim($first[3]);
        $response['name'] = trim($first[0]).' '.trim($first[1]);
    }
    $response['address1'] = trim($content[1] ?? '');
    $response['address2'] = trim($content[2] ?? '');
    $response['client'] = trim($content[3] ?? '');
    $response['address'] = trim($content[4] ?? '');

    $response['result'][0] = $content[5] ? processResult($content[5]) : '';
    $response['result'][1] = isset($content[6]) && $content[6] ? processResult($content[6]) : '';
    $response['result'][2] = isset($content[7]) && $content[7] ? processResult($content[7]) : '';
    //print_r($response);
    return $response;
}

function processResult($result) {
    $content = array_merge([], array_filter(explode(' ', $result)));
    $result = [];

    if (count($content) == 1) {
        $result['level'] = $content[0];
    } elseif(count($content) == 7) {
        $result['id'] = trim($content[0] ?? '');
        $result['level'] = trim($content[1] ?? '');
        $result['location'] = trim($content[2].' '.$content[3].' '.$content[4]);
        $result['duration'] = trim($content[5].' '.$content[6]);
    } elseif(count($content) == 6) {
        $result['id'] = trim($content[0] ?? '');
        $result['level'] = trim($content[1] ?? '');
        $result['location'] = trim($content[2].' '.$content[3]);
        $result['duration'] = trim($content[4].' '.$content[5]);
    } elseif(count($content) == 5) {
        $result['id'] = trim($content[0] ?? '');
        $result['level'] = trim($content[1] ?? '');
        $result['location'] = trim($content[2]);
        $result['duration'] = trim($content[3].' '.$content[4]);
    }
    return $result;
}

function moveFiles($file, $sourceDir, $archiveDir) {
    $output = rename($sourceDir.'/'.$file, $archiveDir.'/'.$file);
    //echo 'Moved files::'.$output;
}
?>
</div>
</div>
</body>
</html>