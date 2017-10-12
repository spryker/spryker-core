<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version;

use Generated\Shared\Transfer\CmsVersionDataTransfer;
use Spryker\Zed\Cms\Dependency\Service\CmsToUtilEncodingInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class VersionMigration implements VersionMigrationInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Cms\Dependency\Service\CmsToUtilEncodingInterface
     */
    protected $utilEncoding;

    /**
     * @var \Spryker\Zed\Cms\Business\Version\Migration\MigrationInterface[]
     */
    protected $migrationHandlers = [];

    /**
     * @param \Spryker\Zed\Cms\Dependency\Service\CmsToUtilEncodingInterface $utilEncoding
     * @param \Spryker\Zed\Cms\Business\Version\Migration\MigrationInterface[] $migrationHandlers
     */
    public function __construct(CmsToUtilEncodingInterface $utilEncoding, array $migrationHandlers)
    {
        $this->utilEncoding = $utilEncoding;
        $this->migrationHandlers = $migrationHandlers;
    }

    /**
     * @param string $cmsVersionOriginData
     * @param string $cmsVersionTargetData
     *
     * @return void
     */
    public function migrate($cmsVersionOriginData, $cmsVersionTargetData)
    {
        $this->handleDatabaseTransaction(function () use ($cmsVersionOriginData, $cmsVersionTargetData) {
            $this->executeMigrateTransaction($cmsVersionOriginData, $cmsVersionTargetData);
        });
    }

    /**
     * @param string $cmsVersionOriginData
     * @param string $cmsVersionTargetData
     *
     * @return void
     */
    protected function executeMigrateTransaction($cmsVersionOriginData, $cmsVersionTargetData)
    {
        $originDataArray = $this->utilEncoding->decodeJson($cmsVersionOriginData, true);
        $targetDataArray = $this->utilEncoding->decodeJson($cmsVersionTargetData, true);

        foreach ($this->migrationHandlers as $migration) {
            $migration->migrate(
                (new CmsVersionDataTransfer())->fromArray($originDataArray),
                (new CmsVersionDataTransfer())->fromArray($targetDataArray)
            );
        }
    }
}
