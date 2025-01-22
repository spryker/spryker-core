<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\KernelApp\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\AppConfigBuilder;
use Generated\Shared\DataBuilder\AppConfigUpdatedBuilder;
use Generated\Shared\Transfer\AppConfigTransfer;
use Generated\Shared\Transfer\AppConfigUpdatedTransfer;
use Orm\Zed\KernelApp\Persistence\SpyAppConfig;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Zed\Testify\Helper\Persistence\FactoryHelperTrait;

class KernelAppHelper extends Module
{
    use DataCleanupHelperTrait;
    use FactoryHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\AppConfigUpdatedTransfer
     */
    public function haveAppConfigUpdatedTransfer(array $seed = []): AppConfigUpdatedTransfer
    {
        $appConfigUpdatedTransfer = (new AppConfigUpdatedBuilder($seed))->build();

        return $appConfigUpdatedTransfer;
    }

    /**
     * @return void
     */
    public function emptyAppConfigTable(): void
    {
        /** @var \Spryker\Zed\KernelApp\Persistence\KernelAppPersistenceFactory $kernelAppPersistenceFactory */
        $kernelAppPersistenceFactory = $this->getFactoryHelper()->getPersistenceFactory();
        $kernelAppPersistenceFactory->createAppConfigPropelQuery()
            ->deleteAll();
    }

    /**
     * @param array $seed
     * @param string|null $appConfigUpdatedAt
     *
     * @return \Generated\Shared\Transfer\AppConfigTransfer
     */
    public function haveAppConfigPersisted(array $seed = [], ?string $appConfigUpdatedAt = null): AppConfigTransfer
    {
        $appConfigTransfer = (new AppConfigBuilder($seed))->build();
        $appConfigEntity = new SpyAppConfig();

        /** @var \Spryker\Zed\KernelApp\Persistence\KernelAppPersistenceFactory $kernelAppPersistenceFactory */
        $kernelAppPersistenceFactory = $this->getFactoryHelper()->getPersistenceFactory('KernelApp');
        $kernelAppPersistenceFactory->createAppConfigMapper()
            ->mapAppConfigTransferToAppConfigEntity($appConfigTransfer, $appConfigEntity);

        if ($appConfigUpdatedAt) {
            $appConfigEntity->setUpdatedAt($appConfigUpdatedAt);
        }

        $appConfigEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($appConfigEntity): void {
            $appConfigEntity->delete();
        });

        return $appConfigTransfer;
    }

    /**
     * @param string $appIdentifier
     * @param \Generated\Shared\Transfer\AppConfigTransfer|null $appConfigTransfer
     *
     * @return void
     */
    public function assertAppConfigIsPersisted(string $appIdentifier, ?AppConfigTransfer $appConfigTransfer = null): void
    {
        /** @var \Spryker\Zed\KernelApp\Persistence\KernelAppPersistenceFactory $kernelAppPersistenceFactory */
        $kernelAppPersistenceFactory = $this->getFactoryHelper()->getPersistenceFactory();
        $appConfigPropelQuery = $kernelAppPersistenceFactory->createAppConfigPropelQuery();
        $appConfigEntity = $appConfigPropelQuery->findOneByAppIdentifier($appIdentifier);

        $this->assertNotNull($appConfigEntity);
        $persistedAppConfigTransfer = $kernelAppPersistenceFactory->createAppConfigMapper()
            ->mapAppConfigEntityToAppConfigTransfer($appConfigEntity, new AppConfigTransfer());

        if ($appConfigTransfer instanceof AppConfigTransfer) {
            $persistedAppConfigData = $persistedAppConfigTransfer->toArray();
            $expectedAppConfigData = $appConfigTransfer->modifiedToArray();

            foreach ($expectedAppConfigData as $key => $value) {
                $this->assertSame($value, $persistedAppConfigData[$key]);
            }
        }
    }
}
