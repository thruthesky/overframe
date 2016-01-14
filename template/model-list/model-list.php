<?php

$in = http_input();

if ( isset($in['name']) ) {
    include_once DIR_OVERFRAME . "/model/$in[name]/install.php";
    $func = "hook_{$in['name']}_$in[do]";
    $func();
}

echo "<table>";
echo "<tr><th>Name</th><th>status</th><th>Action</th></tr>";
foreach( sys()->getModels() as $name ) {

    $path = DIR_OVERFRAME . "/model/$name/install.php";
    $status = '-';
    $action = '-';
    if ( file_exists($path) ) {
        include_once $path;
        $is_installed = "hook_{$name}_is_installed";
        $install = "hook_{$name}_install";
        $uninstall = "hook_{$name}_uninstall";
        if ( function_exists($is_installed) ) {
            $re = $is_installed();
            if ( $re ) {
                $status = 'installed';
                $action = "<a href='?action=model_list&do=uninstall&name=$name'>Un-Install</a>";
            }
            else {
                $status = 'not installed';
                $action = "<a href='?action=model_list&do=install&name=$name'>Install</a>";
            }
        }
    }
    echo "<tr>";
    echo "<td>$name</td>";
    echo "<td>$status</td>";
    echo "<td><b>$action</b></td>";
    echo "</tr>";
}
echo "</table>";