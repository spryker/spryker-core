<?php

namespace SprykerEngine\Zed\Propel\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method PropelDependencyContainer getDependencyContainer()
 */
class PropelFacade extends AbstractFacade
{

    public function cleanPropelSchemaDirectory()
    {
        $this->getDependencyContainer()->createModelPropelSchema()->cleanTargetDirectory();
    }

    public function copySchemaFilesToTargetDirectory()
    {
        $this->getDependencyContainer()->createModelPropelSchema()->copyToTargetDirectory();
    }

    public function copyMergedSchemaFilesToTargetDirectory()
    {
        $this->getDependencyContainer()->createModelMerge()->mergeOrCopySchemaFiles();
    }

}
