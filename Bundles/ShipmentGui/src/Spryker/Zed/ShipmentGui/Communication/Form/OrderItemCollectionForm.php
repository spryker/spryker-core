<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @method \Spryker\Zed\ShipmentGui\Business\SalesFacadeInterface getFacade()
 * @method \Spryker\Zed\ShipmentGui\Communication\SalesCommunicationFactory getFactory()
 * @method \Spryker\Zed\ShipmentGui\Persistence\SalesQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ShipmentGui\SalesConfig getConfig()
 * @method \Spryker\Zed\ShipmentGui\Persistence\SalesRepositoryInterface getRepository()
 */
class OrderItemCollectionForm extends AbstractType
{
    public const FORM_ORDER_ITEM = 'orderItemForm';
    public const CHOICES_ORDER_ITEM = 'choices_order_item';

    public function getBlockPrefix()
    {
        return 'orderItemCollectionForm';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults([
            static::CHOICES_ORDER_ITEM => [],
            'allow_extra_fields' => true,
        ]);

        $resolver->setDefined(self::CHOICES_ORDER_ITEM);
        $resolver->setRequired(self::CHOICES_ORDER_ITEM);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (isset($options[self::CHOICES_ORDER_ITEM]) === false) {
            return;
        }

        foreach($options[self::CHOICES_ORDER_ITEM] as $orderItemChoiceData) {
            $this->addOrderItemSubform(
                $builder,
                [
                    'choice-data' => $orderItemChoiceData,
                ]
            );
        }
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addOrderItemSubform(FormBuilderInterface $builder, array $options)
    {
        $fieldOptions = [
            'data_class' => ItemTransfer::class,
            'required' => true,
//            'validation_groups' => function (FormInterface $form) {
//                if (!$form->has(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS) || !$form->get(CheckoutAddressForm::FIELD_ID_CUSTOMER_ADDRESS)->getData()) {
//                    return [self::GROUP_SHIPPING_ADDRESS];
//                }
//
//                return false;
//            },
        ];

        $builder->add(self::FORM_ORDER_ITEM, OrderItemForm::class, $fieldOptions);

        return $this;
    }


//    public function getParent()
//    {
//        return CollectionType::class;
//    }
}
