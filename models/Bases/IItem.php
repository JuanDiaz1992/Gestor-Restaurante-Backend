<?php

interface  IItem{
    public static function get($identifier = null);
    public function delete();
    public function save();
    public function getId();
    public function setId($id);
}

?>