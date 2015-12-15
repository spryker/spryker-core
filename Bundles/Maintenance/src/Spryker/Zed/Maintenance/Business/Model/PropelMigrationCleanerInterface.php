<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\Model;

interface PropelMigrationCleanerInterface
{

    /**
     * @return bool
     */
    public function clean();

}
