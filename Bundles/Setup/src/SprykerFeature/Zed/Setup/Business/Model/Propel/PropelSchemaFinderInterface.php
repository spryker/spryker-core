<?php

namespace SprykerFeature\Zed\Setup\Business\Model\Propel;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

interface PropelSchemaFinderInterface
{

    /**
     * @return Finder|SplFileInfo[]
     */
    public function getSchemaFiles();

}
