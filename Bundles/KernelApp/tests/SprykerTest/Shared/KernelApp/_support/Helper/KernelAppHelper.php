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
use Orm\Zed\KernelApp\Persistence\SpyAppConfigQuery;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;

class KernelAppHelper extends Module
{
    use DataCleanupHelperTrait;

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
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\AppConfigTransfer
     */
    public function haveAppConfigPersisted(array $seed = []): AppConfigTransfer
    {
        $appConfigTransfer = (new AppConfigBuilder($seed))->build();
        $appConfigEntity = new SpyAppConfig();

        $appConfigData = $appConfigTransfer->toArray();
        $appConfigData[AppConfigTransfer::CONFIG] = json_encode($appConfigData[AppConfigTransfer::CONFIG]);

        $appConfigEntity->fromArray($appConfigData);
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
        $spyAppConfigQuery = SpyAppConfigQuery::create();
        $appConfigEntity = $spyAppConfigQuery->findOneByAppIdentifier($appIdentifier);

        $this->assertNotNull($appConfigEntity);

        if ($appConfigTransfer instanceof AppConfigTransfer) {
            $persistedAppConfigData = $appConfigEntity->toArray();
            $expectedAppConfigData = $appConfigTransfer->modifiedToArray();
            if (isset($expectedAppConfigData[AppConfigTransfer::CONFIG])) {
                $expectedAppConfigData[AppConfigTransfer::CONFIG] = json_encode($expectedAppConfigData[AppConfigTransfer::CONFIG]);
            }

            foreach ($expectedAppConfigData as $key => $value) {
                $this->assertSame($value, $persistedAppConfigData[$key], sprintf('Expected that "%s" is equal to "%s"', is_bool($value) ? $value ? 'true' : 'false' : $value, is_bool($persistedAppConfigData[$key]) ? $persistedAppConfigData[$key] ? 'true' : 'false' : $persistedAppConfigData[$key]));
            }
        }
    }
}
