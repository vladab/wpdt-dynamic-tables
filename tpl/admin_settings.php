<?php
/**
 * Project: wordpress-default
 *
 * Created by vladicabibeskovic.
 * Date: 5.7.15., 17.06 
 */
?>
<div id="normal-sortables" class="meta-box-sortables ui-sortable">
    <div class="postbox">
        <div class="handlediv" title="Click to toggle"><br></div>
        <h3 class="hndle ui-sortable-handle" style="height: 27px">
            <span><div class="dashicons dashicons-admin-generic"></div> Dynamanic Tables Settings</span>
            <div class="pull-right" style="margin-right: 5px">
                <button class="button-primary save_dt_btn"><?php _e('Save Dynamic Table Settings'); ?></button>
            </div>
        </h3>
        <div class="inside">
            <div class="dt_full">
                <label for="dt_child_table"><?php _e('Select Child Table'); ?>
                    <select name="dt_child_table" id="dt_child_table">
                        <?php $tables_options = wdt_get_all_tables() ?>
                        <?php $configuration = (array)json_decode(get_option('dt_configuration', array()), true); ?>
                        <?php foreach( $tables_options as $table ): ?>
                            <?php if( $table['table_type'] == 'mysql' ): ?>
                                <option value="<?php echo $table['id']; ?>"
                                    <?php echo (isset($configuration[$_REQUEST['table_id']]['child_table_id']) && $configuration[$_REQUEST['table_id']]['child_table_id'] == $table['id'])? 'selected' : ''; ?>>
                                    <?php echo $table['title']; ?>
                                </option>
                            <?php endif ?>
                        <?php endforeach; ?>
                    </select>
                </label>
            </div>
            <input type="hidden" id="td_table_id" value="<?php echo $_REQUEST['table_id']; ?>"/>
            <div class="col_3_dt">
                <label for="dt_var1"><?php _e('Variable 1 value:'); ?>
                    <input type="text" id="dt_var1" name="dt_var1" value="<?php echo (isset($configuration[$_REQUEST['table_id']]['var1']))? $configuration[$_REQUEST['table_id']]['var1'] : ''; ?>"/>
                </label>
                <label for="dt_var1_check" class="check">
                    <input type="checkbox" id="dt_var1_check" <?php echo (isset($configuration[$_REQUEST['table_id']]['var1']) && $configuration[$_REQUEST['table_id']]['var1'] == 'TDVALUE')? 'checked' : ''; ?>
                        /><?php _e('Use table cell clicked Value'); ?>
                </label>
            </div>
            <div class="col_3_dt">
                <label for="dt_var2"><?php _e('Variable 2 value'); ?>
                    <input type="text" id="dt_var2" name="dt_var2" value="<?php echo (isset($configuration[$_REQUEST['table_id']]['var2']))? $configuration[$_REQUEST['table_id']]['var2'] : ''; ?>"/>
                </label>
                <label for="dt_var2_check" class="check">
                    <input type="checkbox" id="dt_var2_check" <?php echo (isset($configuration[$_REQUEST['table_id']]['var2']) && $configuration[$_REQUEST['table_id']]['var2'] == 'TDVALUE')? 'checked' : ''; ?>
                        /><?php _e('Use table cell clicked Value'); ?>
                </label>
            </div>
            <div class="col_3_dt">
                <label for="dt_var3"><?php _e('Variable 3 value'); ?>
                    <input type="text" id="dt_var3" name="dt_var3" value="<?php echo (isset($configuration[$_REQUEST['table_id']]['var3']))? $configuration[$_REQUEST['table_id']]['var3'] : ''; ?>"/>
                </label>
                <label for="dt_var3_check" class="check">
                    <input type="checkbox" id="dt_var3_check" <?php echo (isset($configuration[$_REQUEST['table_id']]['var3']) && $configuration[$_REQUEST['table_id']]['var3'] == 'TDVALUE')? 'checked' : ''; ?>
                        /><?php _e('Use table cell clicked Value'); ?>
                </label>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    jQuery( document ).ready(function() {
        jQuery('.save_dt_btn').on('click', function (e) {
            e.preventDefault();
            var dt_child_table_id = jQuery('#dt_child_table').val();
            var table_id = jQuery('#td_table_id').val();
            if( jQuery('#dt_var1_check').is(":checked") ) {
                var var1 = 'TDVALUE';
            } else {
                var var1 = jQuery('#dt_var1').val();
            }
            if( jQuery('#dt_var2_check').is(":checked") ) {
                var var2 = 'TDVALUE';
            } else {
                var var2 = jQuery('#dt_var2').val();
            }
            if( jQuery('#dt_var3_check').is(":checked") ) {
                var var3 = 'TDVALUE';
            } else {
                var var3 = jQuery('#dt_var3').val();
            }
            jQuery.ajax({
                method: "POST",
                url: "/wp-admin/admin-ajax.php",
                data: {
                    action:     'dt_save_dt_table_settings',
                    table_id:   table_id,
                    child_id:   dt_child_table_id,
                    variable1:  var1,
                    variable2:  var2,
                    variable3:  var3
                },
                success: function(data) {
                    alert('Saved');
                }
            });
        });
    });
</script>
<style>
    .dt_full {
        display: inline-block;
        width: 100%;
        margin-bottom: 20px;
    }
    .col_3_dt {
        width: 32%;
        display: inline-block;
    }
    .col_3_dt label {
        display: block;
    }
    .col_3_dt .check {
        margin-left: 104px;
        margin-top: 10px;
    }
</style>