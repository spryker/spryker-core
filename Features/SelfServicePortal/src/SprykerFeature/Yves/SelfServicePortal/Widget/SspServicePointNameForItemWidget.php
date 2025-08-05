<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 */
class SspServicePointNameForItemWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_SERVICE_POINT_NAME = 'servicePointName';

    /**
     * @var string
     */
    protected const PARAMETER_SCHEDULED_AT = 'scheduledAt';

    public function __construct(ItemTransfer $itemTransfer, bool $showServicePointName = true)
    {
        $this->addServicePointNameParameter($itemTransfer, $showServicePointName);
        $this->addScheduledAtParameter($itemTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return 'SspServicePointNameForItemWidget';
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SelfServicePortal/views/service-point-name-for-cart-item/service-point-name-for-cart-item.twig';
    }

    protected function addServicePointNameParameter(ItemTransfer $itemTransfer, bool $showServicePointName = true): void
    {
        $servicePointName = '';

        if ($showServicePointName && $itemTransfer->getServicePoint()) {
            $servicePointName = $itemTransfer->getServicePointOrFail()->getNameOrFail();
        }

        $this->addParameter(static::PARAMETER_SERVICE_POINT_NAME, $servicePointName);
    }

    protected function addScheduledAtParameter(ItemTransfer $itemTransfer): void
    {
        $scheduledAt = null;

        if ($itemTransfer->getMetadata() && $itemTransfer->getMetadataOrFail()->getScheduledAt()) {
            $scheduledAt = $itemTransfer->getMetadataOrFail()->getScheduledAtOrFail();
        }

        $this->addParameter(static::PARAMETER_SCHEDULED_AT, $scheduledAt);
    }
}
