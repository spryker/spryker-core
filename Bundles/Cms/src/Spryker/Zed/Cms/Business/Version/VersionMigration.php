<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version;

use Spryker\Zed\Cms\Business\Version\Handler\MigrationHandlerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class VersionMigration implements VersionMigrationInterface
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @var MigrationHandlerInterface[]
     */
    protected $migrationHandlers = [];

    /**
     * @param MigrationHandlerInterface[] $migrationHandlers
     */
    public function __construct(array $migrationHandlers)
    {
        $this->migrationHandlers = $migrationHandlers;
    }

    /**
     * @param string $cmsVersionOriginData
     * @param string $cmsVersionTargetData
     *
     * @return bool
     */
    public function migrate($cmsVersionOriginData, $cmsVersionTargetData)
    {

        $this->handleDatabaseTransaction(function () use ($cmsVersionOriginData, $cmsVersionTargetData) {
            $originData = json_decode($cmsVersionOriginData, true);
            $targetData = json_decode($cmsVersionTargetData, true);

            foreach ($this->migrationHandlers as $migration) {
                $migration->handle($originData, $targetData);
            }
        });

        return true;
    }
}
