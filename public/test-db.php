<?php
if (extension_loaded('pdo_pgsql')) {
    echo "Driver PostgreSQL AKTIF di Web Server!";
} else {
    echo "Driver PostgreSQL MATI di Web Server!";
}