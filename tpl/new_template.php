<?php
/**
 * Template file for the plain HTML table
 * wpDataTables Module
 *
 * @author cjbug@ya.ru
 * @since 10.10.2012
 *
 **/
?>
<?php if($wpDataTable->getFilteringForm()) { ?>
    <?php do_action('wpdatatables_before_filtering_form', $wpDataTable->getWpId()); ?>
    <div class="wpDataTables wpDataTablesFilter">
        <div id="filterBox_<?php echo $wpDataTable->getId()?>" class="wpDataTableFilterBox">
            <?php foreach( $wpDataTable->getColumns() as $key=>$dataColumn) { ?>
                <?php if($dataColumn->getFilterType()->type != 'null') { ?>
                    <div class="wpDataTableFilterSection" id="<?php echo $wpDataTable->getId().'_'.$key.'_filter' ?>_sections">
                        <label><?php echo $dataColumn->getTitle() ?>:</label>
                        <div id="<?php echo $wpDataTable->getId().'_'.$key.'_filter' ?>"></div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
    <?php do_action('wpdatatables_after_filtering_form', $wpDataTable->getWpId()); ?>
<?php } ?>
<?php do_action('wpdatatables_before_table', $wpDataTable->getWpId()); ?>
    <table table_id="<?php echo $wpDataTable->getWpId(); ?>" id="<?php echo $wpDataTable->getId() ?>" class="<?php echo $wpDataTable->getCSSClasses() ?> wpDataTable" style="<?php echo $wpDataTable->getCSSStyle() ?>" data-description='<?php echo $wpDataTable->getJsonDescription(); ?>' data-wpdatatable_id="<?php echo $wpDataTable->getWpId(); ?>">
        <thead>
        <tr>
            <?php do_action('wpdatatables_before_header', $wpDataTable->getWpId()); ?>
            <?php $expandShown = false; ?>
            <?php foreach($wpDataTable->getColumns() as $dataColumn) { ?><th <?php if(!$expandShown && $dataColumn->isVisibleOnMobiles()){ ?>data-class="expand"<?php $expandShown = true; } ?> <?php if($dataColumn->getHiddenAttr()) { ?>data-hide="<?php echo $dataColumn->getHiddenAttr() ?>"<?php } ?> class="header <?php if( $dataColumn->sortEnabled() ) { ?>sort<?php } ?> <?php echo $dataColumn->getCSSClasses(); ?>" style="<?php echo $dataColumn->getCSSStyle(); ?>"><?php echo $dataColumn->getTitle(); ?></th><?php } ?>
            <?php do_action('wpdatatables_after_header', $wpDataTable->getWpId()); ?>
        </tr>
        </thead>
        <?php if( $wpDataTable->advancedFilterEnabled() && (get_option('wdtRenderFilter') == 'footer') ) { ?>
            <tfoot>
            <?php do_action('wpdatatables_before_footer', $wpDataTable->getWpId()); ?>
            <tr <?php if($wpDataTable->getFilteringForm()) { ?>style="display: none"<?php } ?>>
                <?php foreach( $wpDataTable->getColumns() as $dataColumn) { ?><td class="header <?php if( $dataColumn->sortEnabled() ) { ?>sort<?php } ?> <?php echo $dataColumn->getCSSClasses(); ?>" style="<?php echo $dataColumn->getCSSStyle(); ?>"><?php echo $dataColumn->getTitle(); ?></td><?php } ?>
            </tr>
            <?php do_action('wpdatatables_after_footer', $wpDataTable->getWpId()); ?>
            </tfoot>
        <?php } ?>
        <tbody>
        <?php do_action('wpdatatables_before_first_row', $wpDataTable->getWpId()); ?>
        <?php foreach( $wpDataTable->getDataRows() as $wdtRowIndex => $wdtRowDataArr) { ?>
            <?php do_action('wpdatatables_before_row', $wpDataTable->getWpId(), $wdtRowIndex); ?>
            <tr id="table_<?php echo $wpDataTable->getWpId() ?>_row_<?php echo $wdtRowIndex; ?>">
                <?php foreach( $wpDataTable->getColumnsByHeaders() as $dataColumnHeader => $dataColumn ) { ?>
                    <td <?php echo td_get_table_td_conf($wpDataTable->getWpId(), $wpDataTable->returnCellValue( $wdtRowDataArr[ $dataColumnHeader ], $dataColumnHeader ), $wdtRowDataArr); ?>
                        class="<?php echo $dataColumn->getCSSClasses() ?> dt_sad" style="<?php echo $dataColumn->getCSSStyle();?>"><?php echo $wpDataTable->returnCellValue( $wdtRowDataArr[ $dataColumnHeader ], $dataColumnHeader ); ?></td>
                <?php } ?>
            </tr>
            <?php do_action('wpdatatables_after_row', $wpDataTable->getWpId(), $wdtRowIndex); ?>
        <?php } ?>
        <?php do_action('wpdatatables_after_last_row', $wpDataTable->getWpId()); ?>
        </tbody>

    </table>
<?php do_action('wpdatatables_after_table', $wpDataTable->getWpId()); ?>

    <br/><br/>

<?php if($wpDataTable->isEditable()) { ?>

    <button id="edit_<?php echo $wpDataTable->getId() ?>" class="edit_table DTTT_button DTTT_button_edit" disabled="disabled"><span><?php _e('Edit','wpdatatables');?></span></button>
    <button id="new_<?php echo $wpDataTable->getId() ?>" class="new_table_entry DTTT_button DTTT_button_new"><span><?php _e('New','wpdatatables');?></span></button>
    <button id="delete_<?php echo $wpDataTable->getId() ?>" class="delete_table_entry  DTTT_button DTTT_button_delete" disabled="disabled"><span><?php _e('Delete','wpdatatables');?></span></button>

    <div id="<?php echo $wpDataTable->getId() ?>_edit_dialog" style="display: none" title="<?php _e('Edit','wpdatatables');?>">
        <?php do_action('wpdatatables_before_editor_dialog', $wpDataTable->getWpId()); ?>
        <div class="data_validation_notify" style="display: none"></div>
        <div class="data_saved_notify" style="display: none"><?php _e('Data saved!','wpdatatables'); ?></div>
        <table>
            <thead>
            <tr>
                <th style="width: 20%"></th>
                <th style="width: 80%"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach( $wpDataTable->getColumnsByHeaders() as $dataColumn_key=>$dataColumn ) { ?>
                <tr <?php if( ($dataColumn_key == $wpDataTable->getIdColumnKey()) || ($dataColumn->getInputType() == 'none') || ( ( $wpDataTable->getUserIdColumn() != '' ) && ( $dataColumn_key == $wpDataTable->getUserIdColumn() ) ) ) { ?>style="display: none" <?php if($dataColumn_key == $wpDataTable->getIdColumnKey()) { ?>class="idRow"<?php } } ?>>
                    <td><label for="<?php echo $wpDataTable->getId() ?>_<?php echo $dataColumn_key ?>"><?php echo $dataColumn->getTitle(); ?>:<?php if( $dataColumn->getNotNull() ){ ?> * <?php } ?></label></td>
                    <?php $possibleValues = $dataColumn->getPossibleValues(); ?>
                    <td>
                        <?php if($dataColumn->getInputType() == 'textarea') { ?>
                            <textarea data-input_type="<?php echo $dataColumn->getInputType();?>" class="editDialogInput <?php if( $dataColumn->getNotNull() ){ ?>mandatory<?php } ?>" id="<?php echo $wpDataTable->getId() ?>_<?php echo $dataColumn_key ?>" data-key="<?php echo $dataColumn_key ?>" rows="3" columns="50" data-column_header="<?php echo $dataColumn->getTitle();?>"></textarea>
                        <?php } elseif(($dataColumn->getInputType() == 'selectbox') || ($dataColumn->getInputType() == 'multi-selectbox')) { ?>
                            <select id="<?php echo $wpDataTable->getId() ?>_<?php echo $dataColumn_key ?>" data-input_type="<?php echo $dataColumn->getInputType();?>" data-key="<?php echo $dataColumn_key ?>" class="editDialogInput <?php if( $dataColumn->getNotNull() ){ ?>mandatory<?php } ?>" <?php if($dataColumn->getInputType() == 'multi-selectbox') { ?>multiple="multiple"<?php } ?> data-column_header="<?php echo $dataColumn->getTitle();?>" >
                                <?php foreach($possibleValues as $possibleValue) { ?>
                                    <option value="<?php echo $possibleValue ?>"><?php echo $possibleValue ?></option>
                                <?php } ?>
                            </select>
                        <?php } elseif($dataColumn->getInputType() == 'attachment') { ?>
                            <span class="fileinput-button">
		        <button id="<?php echo $wpDataTable->getId() ?>_<?php echo $dataColumn_key ?>_button" data-rel_input="<?php echo $wpDataTable->getId() ?>_<?php echo $dataColumn_key ?>" class="btn fileupload_<?php echo $wpDataTable->getId() ?>"><?php _e('Browse','wpdatatables');?></button>
		        <input type="hidden" id="<?php echo $wpDataTable->getId() ?>_<?php echo $dataColumn_key ?>" data-key="<?php echo $dataColumn_key ?>" data-input_type="<?php echo $dataColumn->getInputType();?>" class="editDialogInput" />
		    </span>
                            <div id="files_<?php echo $wpDataTable->getId() ?>_<?php echo $dataColumn_key ?>" class="files" style="width: 250px;"></div>
                        <?php } else { ?>
                            <input type="text"
                                   value=""
                                   id="<?php echo $wpDataTable->getId() ?>_<?php echo $dataColumn_key ?>"
                                   data-key="<?php echo $dataColumn_key ?>"
                                   data-column_type="<?php echo $dataColumn->getDataType();?>"
                                   data-column_header="<?php echo $dataColumn->getTitle();?>"
                                   data-input_type="<?php echo $dataColumn->getInputType();?>"
                                   class="editDialogInput  <?php if( $dataColumn->getNotNull() ){ ?>mandatory<?php } ?>
					<?php if($dataColumn->getDataType() == 'float') { ?>maskMoney<?php } ?> 
					<?php if($dataColumn->getInputType() == 'date') { ?>datepicker<?php } ?>"
                                />
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <button id="<?php echo $wpDataTable->getId() ?>_close_edit_dialog" class="btn"><?php _e('Cancel','wpdatatables');?></button>
        <button id="<?php echo $wpDataTable->getId() ?>_prev_edit_dialog" class="btn">&lt;&lt; <?php _e('Prev','wpdatatables');?></button>
        <button id="<?php echo $wpDataTable->getId() ?>_next_edit_dialog" class="btn"><?php _e('Next','wpdatatables');?> &gt;&gt;</button>
        <button id="<?php echo $wpDataTable->getId() ?>_apply_edit_dialog" class="btn"><?php _e('Apply','wpdatatables');?></button>
        <button id="<?php echo $wpDataTable->getId() ?>_ok_edit_dialog" class="btn"><?php _e('OK','wpdatatables');?></button>
        <?php do_action('wpdatatables_after_editor_dialog', $wpDataTable->getWpId()); ?>
    </div>
<?php } ?>