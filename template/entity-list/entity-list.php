<script>
    var url_action = "<?php echo ajax_endpoint()?>&do=entity.ItemList";
</script>
<link rel="stylesheet" href="<?php echo url_overframe()?>/etc/css/ajaxcrud.css">
<script src="<?php echo url_overframe()?>/etc/js/underscore-min.js"></script>
<script src="<?php echo url_overframe()?>/etc/js/ajaxcrud.js"></script>
<script type="text/template" id="entity-table-rows">
    <div class="rows">
        <%= id %>
    </div>
</script>
<?php
$db = database();
$tables = $db->getTables();
$entities = [];
foreach ($tables as $table)
{
    if ( strpos($table, '_entity')  ) {
        $name = str_replace('_node_entity', '', $table);
        $name = str_replace('_meta_entity', '', $name);
        $entities[$name] = $table;

    }
    else continue;
}

foreach ($entities as $name => $table) {
    echo "<span class='btn btn-primary' table='$table'>$name</span>";
}
?>
<div class="display-entity-items">

</div>
