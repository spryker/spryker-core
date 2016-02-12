<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Propel\Business\Model;

interface PropelGroupedSchemaFinderInterface
{

    /**
     * @return array|\Symfony\Component\Finder\SplFileInfo[]
     */
    public function getGroupedSchemaFiles();

}
