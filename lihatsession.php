<?php
session_start();
if (isset($_SESSION)) {
    echo "Data Sesi yang Sedang Berjalan:<br>";
    foreach ($_SESSION as $key => $value) {
        echo $key . " => ";
        if (is_array($value)) {
            echo "[";
            foreach ($value as $item) {
                echo $item . ", ";
            }
            echo "]";
        } else {
            echo $value;
        }
        
        echo "<br>";
    }
} else {
    echo "Tidak ada sesi yang berjalan saat ini.";
}
?>
