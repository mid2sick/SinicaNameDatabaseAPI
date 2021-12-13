<?php
    header("Content-Type:text/html; charset=utf-8");
    require('simplehtmldom_1_9_1/simple_html_dom.php');
    require('function.php');

    // get data from GET method
    $name = $_GET['name'];
    $searchID = $_GET['SN'];
    $CBDB = $_GET['CBDB'];

    // request the page and parse data
    $url = "https://newarchive.ihp.sinica.edu.tw/sncaccgi/sncacFtp?ACTION=TQ,sncacFtpqf,SN=".$searchID.",2nd,search_simple";
    $content = file_get_html($url);
    $basicInform = getBasicInformation($content);
    $refInform = getRefInformation($content);
    $jobInform = getJobInformation($content);

    $returnHTML = '
                    <div class="block">
                        <div class="title">基本資訊
                            <a href="'.$url.'" target="_blank" class="url">連結至完整人物檔案</a>
                        </div>
                        <div class="row">
                            <div class="basic-id">系統號</div>
                            <div class="basic-value">No.'.str_pad($searchID, 6, '0', STR_PAD_LEFT).'</div>
                        </div>
                        <div class="row">
                            <div class="basic-id">姓名</div>
                            <div class="basic-value">('.$basicInform[0].')'.$name.'</div>
                        </div>
                        <div class="row">
                            <div class="basic-id">性別</div>
                            <div class="basic-value">'.$basicInform[1].'</div>
                        </div>
                        <div class="row">
                            <div class="basic-id">中曆生卒年</div>
                            <div class="basic-value">'.$basicInform[2].'</div>
                        </div>
                        <div class="row-last">
                            <div class="basic-id">西曆生卒年</div>
                            <div class="basic-value">'.$basicInform[3].'</div>
                        </div>
                    </div>
                    <div class="block">
                        <div class="title">傳略</div>
                    </div>
                    <div class="block">
                        <div class="title">本文相關履歷</div>
                        <div class="row">
                            <div class="job-id">職稱</div>
                            <div class="job-value">任期</div>
                        </div>';

    for($i = 1; $i < $jobInform[0]; $i ++) {
        $returnHTML = $returnHTML.'
                        <div class="row">
                            <div class="job-id">'.$jobInform[$i][0].'</div>
                            <div class="job-value">'.$jobInform[$i][1].'</div>
                        </div>';
    }                        
    $returnHTML = $returnHTML.'
                        <div class="row-last">
                            <div class="job-id">'.$jobInform[$jobInform[0]][0].'</div>
                            <div class="job-value">'.$jobInform[$jobInform[0]][1].'</div>
                        </div>';
    $returnHTML = $returnHTML.'
                    </div>
                    <div class="block">
                        <div class="title">出處資訊</div>
                        <div class="row">
                            <div class="ref-id">出處</div>
                            <div class="ref-value">';
    for($i = 1; $i <= $refInform[0]; $i ++) {
        $returnHTML = $returnHTML.'
                        <div>'.$refInform[$i].'</div>';
    }
    $returnHTML = $returnHTML.'
                    </div>
                </div>
                <div class="row-last">
                    <div class="ref-id">CBDB</div>
                    <div class="ref-value">'.$CBDB.'</div>
                </div>
            </div>
            ';
    
    echo $returnHTML;
?>