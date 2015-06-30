<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel\Business\Model;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

interface PropelSchemaFinderInterface
{

    /**
     * @return Finder|SplFileInfo[]
     */
    public function getSchemaFiles();

}
