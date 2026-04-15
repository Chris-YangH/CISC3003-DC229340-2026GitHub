<?php

function readCustomers($filename)
{
    $customers = array();

    if (!file_exists($filename)) {
        return $customers;
    }

    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $data = str_getcsv($line, ';');

        if (count($data) >= 12) {
            $customer = array(
                'id' => (int) trim($data[0]),
                'first_name' => trim($data[1]),
                'last_name' => trim($data[2]),
                'email' => trim($data[3]),
                'university' => trim($data[4]),
                'address' => trim($data[5]),
                'city' => trim($data[6]),
                'state' => trim($data[7]),
                'country' => trim($data[8]),
                'postal' => trim($data[9]),
                'phone' => trim($data[10]),
                'sales' => str_replace(' ', '', trim($data[11]))
            );

            $customers[$customer['id']] = $customer;
        }
    }

    return $customers;
}

function readOrders($customer, $filename)
{
    $orders = array();

    if (!file_exists($filename)) {
        return $orders;
    }

    $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $data = str_getcsv($line);

        if (count($data) >= 5 && (int) trim($data[1]) === (int) $customer) {
            $orders[] = array(
                'order_id' => (int) trim($data[0]),
                'customer_id' => (int) trim($data[1]),
                'isbn' => trim($data[2]),
                'title' => trim($data[3]),
                'category' => trim($data[4])
            );
        }
    }

    return $orders;
}

?>
