<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntryGui\Communication\Form\Checkout;

use DateTime;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\ManualOrderEntryGui\Communication\ManualOrderEntryGuiCommunicationFactory getFactory()
 */
class CheckoutForm extends AbstractType
{
    const FIELD_CUSTOMERS = 'customers';

    const OPTION_TEMPLATE_CHOICES = 'template_choices';

    const GROUP_UNIQUE_BLOCK_CHECK = 'unique_block_check';

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
