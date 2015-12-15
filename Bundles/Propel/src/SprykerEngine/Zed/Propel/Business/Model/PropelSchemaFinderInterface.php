<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Propel\Business\Model;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

interface PropelSchemaFinderInterface
{

    /**
     * @return Finder|SplFileInfo[]
     */
    public function getSchemaFiles();

}
