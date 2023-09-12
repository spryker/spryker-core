<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeMerchantPortalGui\Communication\Plugin\ProductOfferMerchantPortalGui;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferMerchantPortalGuiExtension\Dependency\Plugin\ProductOfferFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\ProductOfferShipmentTypeMerchantPortalGui\ProductOfferShipmentTypeMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\ProductOfferShipmentTypeMerchantPortalGui\Communication\ProductOfferShipmentTypeMerchantPortalGuiCommunicationFactory getFactory()
 */
class ShipmentTypeProductOfferFormExpanderPlugin extends AbstractPlugin implements ProductOfferFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `ProductOfferForm` with `shipment-type` form field.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface<mixed>
     */
    public function expand(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        return $this->getFactory()
            ->createShipmentTypeProductOfferFormExpander()
            ->expand($builder, $options);
    }
}
