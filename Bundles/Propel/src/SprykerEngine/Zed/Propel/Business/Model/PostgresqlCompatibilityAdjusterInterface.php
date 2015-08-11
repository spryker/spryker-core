<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel\Business\Model;

interface PostgresqlCompatibilityAdjusterInterface
{

    /**
     * @return void
     */
    public function adjustSchemaFiles();

}
