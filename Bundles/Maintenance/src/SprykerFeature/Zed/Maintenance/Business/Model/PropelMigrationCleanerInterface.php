<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Maintenance\Business\Model;

interface PropelMigrationCleanerInterface
{

    /**
     * @return bool
     */
    public function clean();

}
