<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Business;

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
     * @api
     *
     * @deprecated Use `createDatabase` instead.
     *
     * Specification:
     * - Create database for configured driver if it doesn't exist
     *
     * @return void
     */
    public function createDatabaseIfNotExists();

    /**
     * Specification:
     * - Convert given PHP configuration into json configuration
     * - File is placed in configured phpConfDir
     *
     * @api
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
     * - Delete all migration files and the migration directory.
     *
     * @api
     *
     * @return void
     */
    public function deleteMigrationFilesDirectory();

    /**
     * Specification:
     * - Create database if not exists for configured driver.
     *
     * @api
     *
     * @return void
     */
    public function createDatabase();

    /**
     * Specification:
     * - Drop database for configured driver.
     *
     * @api
     *
     * @return void
     */
    public function dropDatabase();

    /**
     * Specification:
     * - Export database backup for configured driver to `$backupPath`.
     *
     * @api
     *
     * @param string $backupPath
     *
     * @return void
     */
    public function exportDatabase($backupPath);

    /**
     * Specification:
     * - Import database backup for configured driver from `$backupPath`.
     *
     * @api
     *
     * @param string $backupPath
     *
     * @return void
     */
    public function importDatabase($backupPath);
}
