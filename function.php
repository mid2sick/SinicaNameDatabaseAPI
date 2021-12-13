<?php
    // get the data of the first one with name
    /*
    function getData($name) {
        $f = fopen("list.csv", "r");
        $re = array();
        while($row = fgetcsv($f)) {
            if (strpos($row[0], $name) !== false) {       // $row[0] == name, $row[1] == num, $row[2] == CBDB
                array_push($re, $row[0]);
                array_push($re, $row[1]);
                array_push($re, $row[2]);
                break;
            }
        }
        fclose($f);
        return $re;
    }
    */

    // get all data with the name
    function searchAllID($name) {
        echo '<script>console.log("here");</script>';
        $f = fopen("list.csv", "r");
        $re = array();
        array_push($re, 0);     // $re[0] = result num
        while($row = fgetcsv($f)) {
            if (strpos($row[0], $name) !== false) {       // $row[0] == name, $row[1] == num, $row[2] == CBDB
                $cur = array();
                array_push($cur, $row[0]);
                array_push($cur, str_pad($row[1], 6, '0', STR_PAD_LEFT));
                if($row[2] == "null") array_push($cur, "No data");
                else array_push($cur, str_pad($row[2], 7, '0', STR_PAD_LEFT));
                array_push($re, $cur);
                $re[0] ++;
                echo '<script>console.log("find: '.$re[$re[0]][0].', '.$re[$re[0]][1].'");</script>';
            }
        }
        fclose($f);
        return $re;
    }

    function searchRow($content, $str) {
        $find = 0;
        foreach($content->find('tr') as $element) {
            if($element->find("th")[0]->plaintext == $str) {
                $data = $element->find("td")[0]->plaintext;
                $find ++;
            }
        }
        // if we didn't find the data
        if($find == 0) {
            $data = "No data";
            echo '<script>console.log("no data: '.$str.'");</script>';
            $cntData ++;
        }
        return $data;
    }

    function getBasicInformation($content) {
        $fetchData = array();

        array_push($fetchData, searchRow($content, "朝代", $fetchData));
        array_push($fetchData, searchRow($content, "性別", $fetchData));
        array_push($fetchData, searchRow($content, "中曆生卒", $fetchData));
        array_push($fetchData, searchRow($content, "西曆生卒", $fetchData));

        return $fetchData;
    }

    function getRefInformation($content) {
        $fetchData = array();
        array_push($fetchData, 0); // the number of found contents

        foreach($content->find("tr") as $element) {
            if($element->find("th")[0]->plaintext == "傳略") {
                $refList = $element->find("td")[0]->find("table")[0];
                foreach($refList->find("tr") as $ref) {
                    if($ref->find("th")[0]->plaintext == "引文出處") {
                        echo '<script>console.log("find ref title"); </script>';
                        continue;
                    }
                    $fetchData[0] ++;
                    $refName = $ref->find("td")[0]->plaintext;
                    array_push($fetchData, $refName);
                    echo '<script>console.log("find ref: '.$refName.'"); </script>';
                }
                echo '<script>console.log("find ref num: '.$fetchData[0].'"); </script>';
                break;
            }
        }
        return $fetchData;
    }

    function getJobInformation($content) {
        $fetchData = array();
        array_push($fetchData, 0); // the number of found contents

        foreach($content->find("tr") as $element) {
            if($element->find("th")[0]->plaintext == "履歷") {
                $jobList = $element->find("td")[0]->find("table")[0];
                foreach($jobList->find("tr") as $job) {
                    if($job->find("th")[0]->plaintext == "履歷") {
                        continue;
                    }
                    $fetchData[0] ++;
                    $jobName = $job->find("td")[0]->plaintext;
                    $jobTime = $job->find("td")[1]->plaintext;
                    array_push($fetchData, array($jobName, $jobTime));
                    echo '<script>console.log("find job: '.$jobName.', '.$jobTime.'"); </script>';
                }
                echo '<script>console.log("find job num: '.$fetchData[0].'"); </script>';
                break;
            }
        }
        return $fetchData;
    }
?>