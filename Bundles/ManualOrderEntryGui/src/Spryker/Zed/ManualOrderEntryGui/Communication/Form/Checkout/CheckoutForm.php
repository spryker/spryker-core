<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\Checkout;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 */
class CheckoutForm extends AbstractType
{
    //@todo @Artem rename to manual-order-entry TYPE!
    const FIELD_CUSTOMERS = 'customers';

    const OPTION_TEMPLATE_CHOICES = 'template_choices';
    const OPTION_REQUEST = 'request';

    const GROUP_UNIQUE_BLOCK_CHECK = 'unique_block_check';


    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(self::OPTION_REQUEST);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     *
     * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addPluginForms($builder);
    }

     /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
      *
      * @throws \Spryker\Zed\Kernel\Exception\Container\ContainerKeyNotFoundException
     */
    protected function addPluginForms(FormBuilderInterface $builder)
    {
        foreach ($this->getFactory()->getCheckoutFormPlugins() as $formPlugin) {
            $formPlugin->buildForm($builder);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'checkout';
    }

}
