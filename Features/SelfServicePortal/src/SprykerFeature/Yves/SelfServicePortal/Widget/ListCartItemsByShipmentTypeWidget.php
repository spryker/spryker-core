<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 */
class ListCartItemsByShipmentTypeWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_SHIPMENT_TYPE_GROUPS = 'shipmentTypeGroups';

    /**
     * @param iterable<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     */
    public function __construct(iterable $itemTransfers)
    {
        $this->addShipmentTypeGroupsParameter($itemTransfers);
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
        return 'ListCartItemsByShipmentTypeWidget';
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
        return '@SelfServicePortal/views/list-items-by-shipment-type/list-items-by-shipment-type.twig';
    }

    /**
     * @param iterable<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return void
     */
    protected function addShipmentTypeGroupsParameter(iterable $itemTransfers): void
    {
        $shipmentTypeGroups = $this->getFactory()
            ->createItemShipmentTypeGrouper()
            ->groupItemsByShipmentType($itemTransfers);

        $this->addParameter(static::PARAMETER_SHIPMENT_TYPE_GROUPS, $shipmentTypeGroups);
    }
}
