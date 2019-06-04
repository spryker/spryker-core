<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business;

use Generated\Shared\Transfer\SchemaValidationTransfer;

interface PropelFacadeInterface
{
    /**
     * @api
     *
     * @return void
     */
    public function cleanPropelSchemaDirectory();

    /**
     * @api
     *
     * @return void
     */
    public function copySchemaFilesToTargetDirectory();

    /**
     * Specification:
     * - Creates database for configured driver if it doesn't exist
     *
     * @api
     *
     * @deprecated Use `createDatabase()` instead.
     *
     * @return void
     */
    public function createDatabaseIfNotExists();

    /**
     * Specification:
     * - Converts given PHP configuration into json configuration
     * - File is placed in configured phpConfDir
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @return void
     */
    public function convertConfig();

    /**
     * Specification:
     * - Changes schema files to be compatible with PostgreSql (project)
     *
     * @api
     *
     * @return void
     */
    public function adjustPropelSchemaFilesForPostgresql();

    /**
     * Specification:
     * - Changes schema files to be compatible with PostgreSql (core)
     *
     * @api
     *
     * @return void
     */
    public function adjustCorePropelSchemaFilesForPostgresql();

    /**
     * Specification:
     * - Adds missing in PostgreSql functions (project)
     *
     * @api
     *
     * @return void
     */
    public function adjustPostgresqlFunctions();

    /**
     * Specification:
     * - Adds missing in PostgreSql functions (core)
     *
     * @api
     *
     * @return void
     */
    public function adjustCorePostgresqlFunctions();

    /**
     * @api
     *
     * @return \Symfony\Component\Console\Command\Command[]
     */
    public function getConsoleCommands();

    /**
     * @api
     *
     * @return string
     */
    public function getCurrentDatabaseEngine();

    /**
     * @api
     *
     * @return string
     */
    public function getCurrentDatabaseEngineName();

    /**
     * Specification:
     * - Deletes all migration files and the migration directory.
     *
     * @api
     *
     * @return void
     */
    public function deleteMigrationFilesDirectory();

    /**
     * Specification:
     * - Creates database if not exists for configured driver.
     *
     * @api
     *
     * @return void
     */
    public function createDatabase();

    /**
     * Specification:
     * - Drops database for configured driver.
     *
     * @api
     *
     * @return void
     */
    public function dropDatabase();

    /**
     * Specification:
     * - Exports database backup for configured driver to `$backupPath`.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param string $backupPath
     *
     * @return void
     */
    public function exportDatabase($backupPath);

    /**
     * Specification:
     * - Imports database backup for configured driver from `$backupPath`.
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param string $backupPath
     *
     * @return void
     */
    public function importDatabase($backupPath);

    /**
     * Specification:
     * - Validates all schema files.
     * - When attribute value is changed in the same table, the returned transfer object contains all found errors.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SchemaValidationTransfer
     */
    public function validateSchemaFiles(): SchemaValidationTransfer;

    /**
     * Specification:
     * - Validates schema XML files.
     * - Validates against illogical XML issues in a specific file.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SchemaValidationTransfer
     */
    public function validateSchemaXmlFiles(): SchemaValidationTransfer;

    /**
     * Specification:
     * - Runs raw SQL script for dropping all database tables, without dropping the database.
     *
     * @api
     *
     * @return void
     */
    public function dropDatabaseTables(): void;
}
