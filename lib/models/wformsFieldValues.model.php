<?php

class wformsFieldValuesModel extends waModel {

    protected $table = 'wforms_field_values';

    public function deleteNotExists($field_id, $ids) {
        $sql = "DELETE FROM `" . $this->table . "` WHERE `field_id` = " . (int) $field_id . " AND `id` NOT IN ('" . implode("','", $ids) . "')";
        $this->query($sql);
    }

}
