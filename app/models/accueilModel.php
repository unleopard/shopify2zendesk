<?php

/**
 * Description of model
 *
 * @Created by Majoch abdessamad.
 * @Date: 19/05/2020
 * @Mail: majoch.abdessamad@gmail.com
 * @Autor: majoch abdessamad
 * @File: accueil
 */
class accueilModel extends Model
{
    function getParams()
    {
        parent::setClass(parametre::class);
        return parent::selectAll();
    }

}