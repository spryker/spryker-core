<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Propel\Business;

use SprykerEngine\Zed\Kernel\Business\AbstractFacade;
use SprykerEngine\Zed\Kernel\Container;

/**
 * @method PropelDependencyContainer getDependencyContainer()
 */
class PropelFacade extends AbstractFacade
{

    /**
     * @return void
     */
    public function cleanPropelSchemaDirectory()
    {
        $this->getDependencyContainer()->createDirectoryRemover()->execute();
    }

    /**
     * @return void
     */
    public function copySchemaFilesToTargetDirectory()
    {
        $this->getDependencyContainer()->createModelSchema()->copy();
    }

    /**
     * @return void
     */
    public function adjustPropelSchemaFilesForPostgresql()
    {
        $this->getDependencyContainer()->createPostgresqlCompatibilityAdjuster()->adjustSchemaFiles();
    }

    /**
     * @return void
     */
    public function adjustPostgresqlFunctions()
    {
        $this->getDependencyContainer()->createPostgresqlCompatibilityAdjuster()->addMissingFunctions();
    }

    /**
     * @return Container[]
     */
    public function getConsoleCommands()
    {
        return $this->getDependencyContainer()->getConsoleCommands();
    }

}
