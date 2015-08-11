<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;

/**
 * @method PropelDependencyContainer getDependencyContainer()
 */
class PropelFacade extends AbstractFacade
{

    public function cleanPropelSchemaDirectory()
    {
        $this->getDependencyContainer()->createDirectoryRemover()->execute();
    }

    public function copySchemaFilesToTargetDirectory()
    {
        $this->getDependencyContainer()->createModelSchema()->copy();
    }

    public function adjustPropelSchemaFilesForPostgresql() {
        $this->getDependencyContainer()->createPostgresqlCompatibilityAdjuster()->adjustSchemaFiles();
    }

}
