<?php

namespace SprykerFeature\Zed\Setup\Business\Model\Propel;

use SprykerFeature\Zed\Setup\Business\Model\DirectoryRemover;

interface PropelSchemaInterface
{

    public function cleanTargetDirectory();

    public function copyToTargetDirectory();

}
