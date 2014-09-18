<?php

class wformsFieldValuesModel extends wformsSortableModel {

    protected $table = 'wforms_field_values';

    public function deleteNotExists($field_id, $ids) {
        foreach ($ids as &$id) {
            $id = "'".$this->escape($id)."'";
        }
        unset($id);
        
        $sql = "DELETE FROM `" . $this->table . "` WHERE `field_id` = " . (int) $field_id . " AND `id` NOT IN (" . implode(",", $ids) . ")";
        $this->query($sql);
    }

}
