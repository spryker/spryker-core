<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business;

use Generated\Shared\Transfer\SchemaValidationTransfer;
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
     * @deprecated Use `createDatabase()` instead.
     *
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
     * @deprecated Will be removed without replacement.
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
     * @deprecated Please add the Commands directly to your ConsoleDependencyProvider.
     *
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

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function deleteMigrationFilesDirectory()
    {
        $this->getFactory()->createMigrationDirectoryRemover()->execute();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function createDatabase()
    {
        $this->getFactory()->createPropelDatabaseAdapterCollection()->getAdapter()->createIfNotExists();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function dropDatabase()
    {
        $this->getFactory()->createPropelDatabaseAdapterCollection()->getAdapter()->dropDatabase();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $backupPath
     *
     * @return void
     */
    public function exportDatabase($backupPath)
    {
        $this->getFactory()->createPropelDatabaseAdapterCollection()->getAdapter()->exportDatabase($backupPath);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $backupPath
     *
     * @return void
     */
    public function importDatabase($backupPath)
    {
        $this->getFactory()->createPropelDatabaseAdapterCollection()->getAdapter()->importDatabase($backupPath);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SchemaValidationTransfer
     */
    public function validateSchemaFiles(): SchemaValidationTransfer
    {
        return $this->getFactory()->createSchemaValidator()->validate();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SchemaValidationTransfer
     */
    public function validateSchemaXmlFiles(): SchemaValidationTransfer
    {
        return $this->getFactory()->createSchemaXmlValidator()->validate();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function dropDatabaseTables(): void
    {
        $this->getFactory()
            ->createPropelDatabaseAdapterCollection()
            ->getAdapter()
            ->dropTables();
    }
}
