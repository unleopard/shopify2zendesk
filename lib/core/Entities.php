<?php

/**
 * Description of Entities
 *
 * @Created by Majoch abdessamad.
 * @Date: 19/05/2020
 * @Mail: majoch.abdessamad@gmail.com
 * @Autor: majoch abdessamad
 * @File: Entities
 */

abstract class Entities {

    public $table = null;
    public $metadata = null;
    public $primaryKey = null;
    public $foreignKey = null;

    public abstract function tableShow();
}
