<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;
use Symfony\Component\Form\FormView;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 */
class SspAddressFormItemsByShipmentTypeWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_SHIPMENT_TYPE_GROUPS = 'shipmentTypeGroups';

  /**
   * @param \Symfony\Component\Form\FormView $checkoutAddressForm
   */
    public function __construct(FormView $checkoutAddressForm)
    {
        $this->addShipmentTypeGroupsParameter($checkoutAddressForm);
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
        return 'SspAddressFormItemsByShipmentTypeWidget';
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
        return '@SelfServicePortal/views/address-form-items-by-shipment-type/address-form-items-by-shipment-type.twig';
    }

    /**
     * @param \Symfony\Component\Form\FormView $checkoutAddressForm
     *
     * @return void
     */
    protected function addShipmentTypeGroupsParameter(FormView $checkoutAddressForm): void
    {
        $shipmentTypeGroups = $this->getFactory()
            ->createAddressFormItemShipmentTypeGrouper()
            ->groupItemsByShipmentType($checkoutAddressForm);

        $this->addParameter(static::PARAMETER_SHIPMENT_TYPE_GROUPS, $shipmentTypeGroups);
    }
}
