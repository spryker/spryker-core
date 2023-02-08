<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PublishAndSynchronizeHealthCheck\Communication\Plugin\HealthCheck;

use DateInterval;
use DateTime;
use Generated\Shared\Transfer\HealthCheckServiceResponseTransfer;
use Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer;
use RuntimeException;
use Spryker\Shared\HealthCheckExtension\Dependency\Plugin\HealthCheckPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheck\Business\PublishAndSynchronizeHealthCheckFacadeInterface getFacade()
 * @method \Spryker\Zed\PublishAndSynchronizeHealthCheck\PublishAndSynchronizeHealthCheckConfig getConfig()
 */
class PublishAndSynchronizeWriteHealthCheckPlugin extends AbstractPlugin implements HealthCheckPluginInterface
{
    /**
     * @var string
     */
    public const PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_SERVICE_NAME = 'publish-and-synchronize';

    /**
     * @var string
     */
    public const ERROR_MESSAGE = 'Health check entity has not been updated.';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getName(): string
    {
        return static::PUBLISH_AND_SYNCHRONIZE_HEALTH_CHECK_SERVICE_NAME;
    }

    /**
     * {@inheritDoc}
     * - This plugin will save or update a known entity.
     * - Entity is used by other plugins to validate that the P&S process works as expected.
     * - Returns successful response when entity was properly saved.
     * - Returns error response when entity was not saved/updated properly.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\HealthCheckServiceResponseTransfer
     */
    public function check(): HealthCheckServiceResponseTransfer
    {
        $healthCheckServiceResponseTransfer = new HealthCheckServiceResponseTransfer();

        $publishAndSynchronizeHealthCheckTransfer = $this->getFacade()->savePublishAndSynchronizeHealthCheckEntity();

        $healthCheckServiceResponseTransfer->setStatus(true);

        if (!$this->isValid($publishAndSynchronizeHealthCheckTransfer)) {
            $healthCheckServiceResponseTransfer->setStatus(false);
            $healthCheckServiceResponseTransfer->setMessage(static::ERROR_MESSAGE);
        }

        return $healthCheckServiceResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PublishAndSynchronizeHealthCheckTransfer $publishAndSynchronizeHealthCheckTransfer
     *
     * @throws \RuntimeException
     *
     * @return bool
     */
    protected function isValid(PublishAndSynchronizeHealthCheckTransfer $publishAndSynchronizeHealthCheckTransfer): bool
    {
        if (!$publishAndSynchronizeHealthCheckTransfer->getUpdatedAt()) {
            return false;
        }

        $dateInterval = DateInterval::createFromDateString($this->getConfig()->getValidationThreshold());
        if ($dateInterval === false) {
            throw new RuntimeException('Cannot create a DateInterval from `PublishAndSynchronizeHealthCheckConfig::getValidationThreshold()`');
        }

        $now = new DateTime();
        $maxAge = $now->sub($dateInterval);

        $storageDataUpdatedAt = new DateTime($publishAndSynchronizeHealthCheckTransfer->getUpdatedAtOrFail());

        if ($maxAge > $storageDataUpdatedAt) {
            return false;
        }

        return true;
    }
}
