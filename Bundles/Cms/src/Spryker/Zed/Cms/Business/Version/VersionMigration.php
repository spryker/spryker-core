<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version;

use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class VersionMigration implements VersionMigrationInterface
{

    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Cms\Business\Version\Handler\MigrationHandlerInterface[]
     */
    protected $migrationHandlers = [];

    /**
     * @param \Spryker\Zed\Cms\Business\Version\Handler\MigrationHandlerInterface[] $migrationHandlers
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
