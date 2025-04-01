<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspServiceManagement\Widget;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerFeature\Yves\SspServiceManagement\SspServiceManagementConfig getConfig()
 * @method \SprykerFeature\Yves\SspServiceManagement\SspServiceManagementFactory getFactory()
 */
class ServicePointNameForItemWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_SERVICE_POINT_NAME = 'servicePointName';

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     */
    public function __construct(ItemTransfer $itemTransfer)
    {
        $this->addServicePointNameParameter($itemTransfer);
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
        return 'ServicePointNameForItemWidget';
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
        return '@SspServiceManagement/views/service-point-name-for-cart-item/service-point-name-for-cart-item.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addServicePointNameParameter(ItemTransfer $itemTransfer): void
    {
        $servicePointName = '';

        if ($itemTransfer->getServicePoint()) {
            $servicePointName = $itemTransfer->getServicePointOrFail()->getNameOrFail();
        }

        $this->addParameter(static::PARAMETER_SERVICE_POINT_NAME, $servicePointName);
    }
}
