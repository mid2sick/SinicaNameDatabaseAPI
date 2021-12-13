<?php
    header("Content-Type:text/html; charset=utf-8");
    require('simplehtmldom_1_9_1/simple_html_dom.php');
    require('function.php');
    $name = $_POST['sname'];
    $allID = NULL;
    $content = NULL;
    if($name != "") {
        $allID = searchAllID($name);
        echo '<script>console.log("'.$allID[0].'");</script>';
        if($allID[0] != 0) {
            echo '<script>console.log("'.$allID[0].'");</script>';
            $url = "https://newarchive.ihp.sinica.edu.tw/sncaccgi/sncacFtp?ACTION=TQ,sncacFtpqf,SN=".$allID[1][1].",2nd,search_simple";
            echo '<script>console.log("'.$url.'");</script>';
            $content = file_get_html($url);
            $basicInform = getBasicInformation($content);
            $refInform = getRefInformation($content);
            $jobInform = getJobInformation($content);
        }
    }

    // echo the html file
    if($allID == NULL) {
        echo '
        <!doctype html>
        <html class="no-js" lang="en" dir="ltr">
            <head>
                <meta charset="utf-8">
                <title>資料 API</title>
                <link rel="stylesheet" href="index.css">
            </head>
            <body>
                <div class="page">
                    <section class="leftSection">
                        <form method="post" action="#" class="question">
                            <label for="sname">請輸入欲查詢的人名:</label>
                            <input type="text" id="sname" name="sname"><br><br>
                            <input type="submit" value="Submit">
                        </form>
                    </section>
                    <section class="rightSection emptyRight">
                    </section>
                </div>
            </body>
        </html>
        ';
    } else if($allID[0] == 0) {
        echo '
        <!doctype html>
        <html class="no-js" lang="en" dir="ltr">
            <head>
                <meta charset="utf-8">
                <title>資料 API</title>
                <link rel="stylesheet" href="index.css">
            </head>
            <body>
                <div class="page">
                    <section class="leftSection">
                        <form method="post" action="#" class="question">
                            <label for="sname">請輸入欲查詢的人名:</label>
                            <input type="text" id="sname" name="sname"><br><br>
                            <input type="submit" value="Submit">
                        </form>
                    </section>
                    <section class="rightSection">
                        <div>查無此人，請重新查詢</div>
                    </section>
                </div>
            </body>
        </html>
        ';
    } else {
        echo '
        <!doctype html>
        <html class="no-js" lang="en" dir="ltr">
            <head>
                <meta charset="utf-8">
                <title>資料 API</title>
                <link rel="stylesheet" href="index.css">
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script src="changePage.js"></script>
            </head>
            <body>
                <div class="page">
                    <section class="leftSection">
                        <form method="post" action="#" class="question">
                            <label for="sname">請輸入欲查詢的人名:</label>
                            <input type="text" id="sname" name="sname"><br><br>
                            <input type="submit" value="Submit">
                        </form>
                    </section>
                    <section class="rightSection">
                        <div class="top">
                            <div class="name">'.$name.'</div>
                        </div>
                        <div class="buttonList">';
                        for($i = 1; $i <= $allID[0]; $i ++) {
                            echo '<button type="button" onclick="getPage(\''.$allID[$i][0].'\', \''.$allID[$i][1].'\', \''.$allID[$i][2].'\');">'.$allID[$i][0].'-'.$allID[$i][1].'</button>';
                            // echo '<script>console.log("add button: '.$allID[$i][0].', '.str_pad($allID[$i][1], 6, 0, STR_PAD_LEFT).'");</script>';
                        }
                        echo '
                        </div>
                        <div id="personPage">
                            <div class="block">
                                <div class="title">基本資訊
                                    <a href="'.$url.'" target="_blank" class="url">連結至完整人物檔案</a>
                                </div>
                                <div class="row">
                                    <div class="basic-id">系統號</div>
                                    <div class="basic-value">No.'.$allID[1][1].'</div>
                                </div>
                                <div class="row">
                                    <div class="basic-id">姓名</div>
                                    <div class="basic-value">('.$basicInform[0].')'.$allID[1][0].'</div>
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
                            echo '
                                <div class="row">
                                    <div class="job-id">'.$jobInform[$i][0].'</div>
                                    <div class="job-value">'.$jobInform[$i][1].'</div>
                                </div>';
                        }                        
                        echo '
                                <div class="row-last">
                                    <div class="job-id">'.$jobInform[$jobInform[0]][0].'</div>
                                    <div class="job-value">'.$jobInform[$jobInform[0]][1].'</div>
                                </div>';
                        echo '
                            </div>
                            <div class="block">
                                <div class="title">出處資訊</div>
                                <div class="row">
                                    <div class="ref-id">出處</div>
                                    <div class="ref-value">';
                        for($i = 1; $i <= $refInform[0]; $i ++) {
                            echo '
                                        <div>'.$refInform[$i].'</div>';
                        }
                        echo '
                                    </div>
                                </div>
                                <div class="row-last">
                                    <div class="ref-id">CBDB</div>
                                    <div class="ref-value">'.$allID[1][2].'</div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </body>
        </html>
        ';
    }
?>
