<?php

namespace SprykerEngine\Zed\Propel\Business\Model;

interface PropelSchemaInterface
{

    public function cleanTargetDirectory();

    public function copyToTargetDirectory();

}
