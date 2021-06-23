<?php
    $a = 0;

    for ($i = 1; $i <= 3; $i++) {
        
        $month = date('m') + $a;
    
            if ($month > 12) {
                $month = $month % 12;
                $year = date('Y') + $a;
            } else {
                $year = date('Y');
            }

        $a++;
        echo $a.': '.$month.'<br>';
    }
?>