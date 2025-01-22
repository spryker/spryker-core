<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\KernelApp\Persistence\Mapper;

use Generated\Shared\Transfer\AppConfigTransfer;
use Orm\Zed\KernelApp\Persistence\SpyAppConfig;
use Spryker\Zed\KernelApp\Dependency\Service\KernelAppToUtilEncodingServiceInterface;

class AppConfigMapper
{
    /**
     * @var \Spryker\Zed\KernelApp\Dependency\Service\KernelAppToUtilEncodingServiceInterface
     */
    protected KernelAppToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Zed\KernelApp\Dependency\Service\KernelAppToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(KernelAppToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Orm\Zed\KernelApp\Persistence\SpyAppConfig $appConfigEntity
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     *
     * @return \Generated\Shared\Transfer\AppConfigTransfer
     */
    public function mapAppConfigEntityToAppConfigTransfer(
        SpyAppConfig $appConfigEntity,
        AppConfigTransfer $appConfigTransfer
    ): AppConfigTransfer {
        $appConfigData = $appConfigEntity->toArray();
        $appConfigData[AppConfigTransfer::CONFIG] = $this->utilEncodingService->decodeJson((string)$appConfigEntity->getConfig(), true);
        $appConfigData[AppConfigTransfer::MESSAGE_CHANNELS] = $appConfigData[AppConfigTransfer::CONFIG][AppConfigTransfer::MESSAGE_CHANNELS] ?? [];
        unset($appConfigData[AppConfigTransfer::CONFIG][AppConfigTransfer::MESSAGE_CHANNELS]);

        return $appConfigTransfer->fromArray($appConfigData, true);
    }

    /**
     * @param \Generated\Shared\Transfer\AppConfigTransfer $appConfigTransfer
     * @param \Orm\Zed\KernelApp\Persistence\SpyAppConfig $appConfigEntity
     *
     * @return \Orm\Zed\KernelApp\Persistence\SpyAppConfig
     */
    public function mapAppConfigTransferToAppConfigEntity(
        AppConfigTransfer $appConfigTransfer,
        SpyAppConfig $appConfigEntity
    ): SpyAppConfig {
        $appConfigData = $appConfigTransfer->modifiedToArray();
        $appConfigData[AppConfigTransfer::CONFIG] = $this->utilEncodingService->encodeJson(
            [AppConfigTransfer::MESSAGE_CHANNELS => array_values($appConfigTransfer->getMessageChannels())]
                + $appConfigTransfer->getConfig(),
        );

        return $appConfigEntity->fromArray($appConfigData);
    }
}
