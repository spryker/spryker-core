<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Propel\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\Console\Command\Command;

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
     * @return Command[]
     */
    public function getConsoleCommands()
    {
        return $this->getDependencyContainer()->getConsoleCommands();
    }

}
