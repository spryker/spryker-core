<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Plugin\ProductOfferMerchantPortalGui;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferMerchantPortalGuiExtension\Dependency\Plugin\ProductOfferFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\ProductOfferServicePointMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\ProductOfferServicePointMerchantPortalGuiCommunicationFactory getFactory()
 */
class ServiceProductOfferFormExpanderPlugin extends AbstractPlugin implements ProductOfferFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `ProductOfferForm` with Service Point and Service fields.
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
        return $this->getFactory()->createServiceProductOfferFormExpander()->expand($builder, $options);
    }
}
