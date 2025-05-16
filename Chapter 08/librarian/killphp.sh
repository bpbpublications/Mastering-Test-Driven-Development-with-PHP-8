kill -9 $(ps -C "php" | tail -n 1 | awk '{print $1}')
