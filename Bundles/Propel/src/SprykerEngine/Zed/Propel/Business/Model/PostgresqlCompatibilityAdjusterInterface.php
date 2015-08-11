<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel\Business\Model;

interface PostgresqlCompatibilityAdjusterInterface
{


    public function adjustSchemaFiles();

    public function addMissingFunctions();

}
