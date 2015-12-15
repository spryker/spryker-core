<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Propel\Business\Model;

interface PostgresqlCompatibilityAdjusterInterface
{

    public function adjustSchemaFiles();

    public function addMissingFunctions();

}
