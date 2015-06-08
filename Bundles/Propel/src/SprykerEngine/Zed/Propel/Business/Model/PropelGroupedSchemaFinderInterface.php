<?php

namespace SprykerEngine\Zed\Propel\Business\Model;

interface PropelGroupedSchemaFinderInterface
{

    /**
     * @return array[SplFileInfo[]]
     */
    public function getGroupedSchemaFiles();

}
