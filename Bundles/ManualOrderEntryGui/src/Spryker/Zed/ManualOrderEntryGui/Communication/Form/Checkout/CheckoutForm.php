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
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
//        $resolver->setRequired(static::OPTION_TEMPLATE_CHOICES);
//
//        $resolver->setDefaults([
//            'validation_groups' => function (FormInterface $form) {
//                $defaultData = $form->getConfig()->getData();
//                $formData = $form->getData();
//
//                if (!array_key_exists(static::FIELD_NAME, $defaultData)) {
//                    return [Constraint::DEFAULT_GROUP, static::GROUP_UNIQUE_BLOCK_CHECK];
//                }
//
//                if ($defaultData[static::FIELD_NAME] !== $formData[static::FIELD_NAME]) {
//                    return [Constraint::DEFAULT_GROUP, static::GROUP_UNIQUE_BLOCK_CHECK];
//                }
//
//                return [Constraint::DEFAULT_GROUP];
//            },
//        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addPluginForms($builder);
    }

     /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
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
