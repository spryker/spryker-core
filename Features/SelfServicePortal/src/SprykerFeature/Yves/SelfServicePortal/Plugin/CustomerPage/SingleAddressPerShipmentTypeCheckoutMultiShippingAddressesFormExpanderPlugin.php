<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Plugin\CustomerPage;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerPageExtension\Dependency\Plugin\CheckoutMultiShippingAddressesFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 */
class SingleAddressPerShipmentTypeCheckoutMultiShippingAddressesFormExpanderPlugin extends AbstractPlugin implements CheckoutMultiShippingAddressesFormExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
    }

    /**
     * {@inheritDoc}
     * - Expands checkout multi-shipping address form with single address per shipment type checkbox.
     * - Only adds checkbox for applicable shipment types (delivery and on-site service).
     * - Skips bundle items.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $this->getFactory()
            ->createSingleAddressPerShipmentTypeAddressStepForm()
            ->buildForm($builder, $options);

        return $builder;
    }
}
