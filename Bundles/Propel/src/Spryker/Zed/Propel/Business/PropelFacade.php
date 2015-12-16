<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Propel\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\Console\Command\Command;

/**
 * @method PropelBusinessFactory getBusinessFactory()
 */
class PropelFacade extends AbstractFacade
{

    /**
     * @return void
     */
    public function cleanPropelSchemaDirectory()
    {
        $this->getBusinessFactory()->createDirectoryRemover()->execute();
    }

    /**
     * @return void
     */
    public function copySchemaFilesToTargetDirectory()
    {
        $this->getBusinessFactory()->createModelSchema()->copy();
    }

    /**
     * @return void
     */
    public function adjustPropelSchemaFilesForPostgresql()
    {
        $this->getBusinessFactory()->createPostgresqlCompatibilityAdjuster()->adjustSchemaFiles();
    }

    /**
     * @return void
     */
    public function adjustPostgresqlFunctions()
    {
        $this->getBusinessFactory()->createPostgresqlCompatibilityAdjuster()->addMissingFunctions();
    }

    /**
     * @return Command[]
     */
    public function getConsoleCommands()
    {
        return $this->getBusinessFactory()->getConsoleCommands();
    }

}
