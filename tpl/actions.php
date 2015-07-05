<?php
/**
 * Project: wordpress-default
 *
 * Created by vladicabibeskovic.
 * Date: 5.7.15., 15.39 
 */
?>
<script type="text/javascript">
    var dt_count_tables = window.dt_count_tables + 1 || 1;
    jQuery( document ).ready(function() {
        jQuery('.dt_sad').off('click').on('click', function() {
            var table_id = jQuery(this).closest('table').attr('table_id');
            var var1 = jQuery(this).attr('var1');
            var var2 = jQuery(this).attr('var2');
            var var3 = jQuery(this).attr('var3');
            if( typeof var1 != 'undefined' || typeof var2 != 'undefined' || typeof var3 != 'undefined' ) {
                var div_id = 'td_child_' + table_id;
                var table_div_id = jQuery(this).closest('.wpDataTablesWrapper').attr('id');
                if (jQuery(document).find('#' + div_id).length == 0) {
                    jQuery('#' + table_div_id).parent().append('<div id="' + div_id + '"></div>');
                }
                jQuery('#' + div_id).html('loading...');

                jQuery.ajax({
                    method: "POST",
                    url: "/wp-admin/admin-ajax.php",
                    data: {
                        action: 'dt_get_table',
                        table_id: table_id,
                        variable1: var1,
                        variable2: var2,
                        variable3: var3
                    },
                    success: function (data) {
                        jQuery('#' + div_id).html(data);
                        jQuery('#' + div_id + ' .wpDataTable').attr('id', 'table_' + dt_count_tables);
                        jQuery('#' + div_id + ' .wpDataTable').show();
                        jQuery('#table_' + dt_count_tables).DataTable();
                    }
                });
            }
        });
    });
</script>