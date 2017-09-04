<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Propel\Business\PropelBusinessFactory getFactory()
 */
class PropelFacade extends AbstractFacade implements PropelFacadeInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function cleanPropelSchemaDirectory()
    {
        $this->getFactory()->createDirectoryRemover()->execute();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function copySchemaFilesToTargetDirectory()
    {
        $this->getFactory()->createModelSchema()->copy();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function createDatabaseIfNotExists()
    {
        $this->getFactory()->createDatabaseCreator()->createDatabaseIfNotExists();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function convertConfig()
    {
        $this->getFactory()->createConfigConverter()->convertConfig();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function adjustPropelSchemaFilesForPostgresql()
    {
        $this->getFactory()->createPostgresqlCompatibilityAdjuster()->adjustSchemaFiles();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function adjustCorePropelSchemaFilesForPostgresql()
    {
        $this->getFactory()->createCorePostgresqlCompatibilityAdjuster()->adjustSchemaFiles();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function adjustPostgresqlFunctions()
    {
        $this->getFactory()->createPostgresqlCompatibilityAdjuster()->addMissingFunctions();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function adjustCorePostgresqlFunctions()
    {
        $this->getFactory()->createCorePostgresqlCompatibilityAdjuster()->addMissingFunctions();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Symfony\Component\Console\Command\Command[]
     */
    public function getConsoleCommands()
    {
        return $this->getFactory()->getConsoleCommands();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getCurrentDatabaseEngine()
    {
        return $this->getFactory()->getConfig()->getCurrentDatabaseEngine();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getCurrentDatabaseEngineName()
    {
        return $this->getFactory()->getConfig()->getCurrentDatabaseEngineName();
    }

}
