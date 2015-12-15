<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Propel\Business\Model;

interface PropelGroupedSchemaFinderInterface
{

    /**
     * @return array[SplFileInfo[]]
     */
    public function getGroupedSchemaFiles();

}
