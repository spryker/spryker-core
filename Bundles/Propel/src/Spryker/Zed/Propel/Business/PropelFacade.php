<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business;

use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Generated\Shared\Transfer\SchemaValidationTransfer;
use PDOException;
use Propel\Runtime\Connection\Exception\ConnectionException;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\Propel\Business\PropelBusinessFactory getFactory()
 */
class PropelFacade extends AbstractFacade implements PropelFacadeInterface
{
    /**
     * {@inheritDoc}
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
     * @inheritDoc
     *
     * @api
     *
     * @return string
     */
    public function getSchemaDirectory(): string
    {
        return $this->getFactory()
            ->createPropelConfigReader()
            ->getSchemaDirectory();
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link createDatabase()} instead.
     *
     * @return void
     */
    public function createDatabaseIfNotExists()
    {
        $this->getFactory()->createDatabaseCreator()->createDatabaseIfNotExists();
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Please add the Commands directly to your ConsoleDependencyProvider.
     *
     * @return array<\Symfony\Component\Console\Command\Command>
     */
    public function getConsoleCommands()
    {
        return $this->getFactory()->getConsoleCommands();
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return string
     */
    public function getCurrentDatabaseEngineName()
    {
        return $this->getFactory()->getConfig()->getCurrentDatabaseEngineName();
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
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

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function executeDatabaseHealthCheck(): HealthCheckServiceResponseTransfer
    {
        return $this->getFactory()->createPropelHealthChecker()->executeHealthCheck();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $tableName
     *
     * @return bool
     */
    public function tableExists(string $tableName): bool
    {
        try {
            return $this->getFactory()->createPropelDatabaseAdapterCollection()->getAdapter()->tableExists($tableName);
        } catch (ConnectionException | PDOException $exception) {
            return false;
        }
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function loadPropelTableMap(): bool
    {
        return $this->getFactory()->createPropelTableMapLoader()->loadTableMap();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     */
    public function isCollationCaseSensitive(): bool
    {
        return $this->getFactory()->createPropelConfigReader()->isCollationCaseSensitive();
    }
}
