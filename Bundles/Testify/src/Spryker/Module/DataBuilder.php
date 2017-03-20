<?php
namespace Spryker\Module;

class DataBuilder extends \Codeception\Module
{
    protected $config = ['dataFiles' => '../*/tests/_data/dataFactory.php'];

    public function _beforeSuite()
    {
        // find all datafiles
        // require them
    }

    public function haveData($dtoClass)
    {

    }
}