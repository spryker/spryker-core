<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Business\Strategy;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface;

class StoreYvesBaseUrlGetStrategy implements BaseUrlGetStrategyInterface
{
    /**
     * @var int
     */
    protected const PORT_HTTPS = 443;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig
     */
    protected AvailabilityNotificationConfig $availabilityNotificationConfig;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface
     */
    protected AvailabilityNotificationToStoreFacadeInterface $storeFacade;

    /**
     * @param \Spryker\Zed\AvailabilityNotification\AvailabilityNotificationConfig $availabilityNotificationConfig
     * @param \Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        AvailabilityNotificationConfig $availabilityNotificationConfig,
        AvailabilityNotificationToStoreFacadeInterface $storeFacade
    ) {
        $this->availabilityNotificationConfig = $availabilityNotificationConfig;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return bool
     */
    public function isApplicable(?StoreTransfer $storeTransfer = null): bool
    {
        if (!$storeTransfer || !$storeTransfer->getName()) {
            return false;
        }

        /* Required by infrastructure, exists only for BC with DMS OFF mode. */
        if ($this->storeFacade->isDynamicStoreEnabled() === false) {
            return isset($this->availabilityNotificationConfig->getStoreToYvesHostMapping()[$storeTransfer->getNameOrFail()]);
        }

        return isset($this->availabilityNotificationConfig->getRegionToYvesHostMapping()[$this->availabilityNotificationConfig->getCurrentRegion()]);
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer|null $storeTransfer
     *
     * @return string
     */
    public function getBaseUrl(?StoreTransfer $storeTransfer = null): string
    {
        if (!$storeTransfer) {
            return '';
        }

        /* Required by infrastructure, exists only for BC with DMS OFF mode. */
        if ($this->storeFacade->isDynamicStoreEnabled() === false) {
            $yvesHost = $this->availabilityNotificationConfig->getStoreToYvesHostMapping()[$storeTransfer->getNameOrFail()];

            return $this->generateBaseUrl($yvesHost);
        }

        $yvesHost = $this->availabilityNotificationConfig->getRegionToYvesHostMapping()[$this->availabilityNotificationConfig->getCurrentRegion()];

        return $this->generateBaseUrl($yvesHost);
    }

    /**
     * @param string $yvesHost
     *
     * @return string
     */
    protected function generateBaseUrl(string $yvesHost): string
    {
        return sprintf(
            '%s://%s',
            $this->availabilityNotificationConfig->getBaseUrlYvesPort() === static::PORT_HTTPS ? 'https' : 'http',
            $yvesHost,
        );
    }
}
