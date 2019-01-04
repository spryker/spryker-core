<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\Sales\Business\SalesFacadeInterface getFacade()
 * @method \Spryker\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 * @method \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Sales\SalesConfig getConfig()
 * @method \Spryker\Zed\Sales\Persistence\SalesRepositoryInterface getRepository()
 */
class ShipmentForm extends AbstractType
{
    public const FORM_ADDRESS = 'addressForm';
    public const FORM_ORDER_ITEMS = 'orderItemsForm';
    public const FIELD_SHIPMENT_DATE = 'delivery_date';
    public const FIELD_SHIPMENT_METHOD = 'shipment_method';
    public const OPTION_SHIPMENT_METHOD = 'data';
    public const FIELD_DELIVERY_ADDRESS = 'delivery_address';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'shipmentForm';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(OrderItemCollectionForm::CHOICES_ORDER_ITEM);
        $resolver->setRequired(OrderItemCollectionForm::CHOICES_ORDER_ITEM);

        $resolver->setRequired(AddressForm::OPTION_SALUTATION_CHOICES);
        $resolver->setRequired(AddressForm::OPTION_COUNTRY_CHOICES);

        $resolver->setDefaults([
            OrderItemCollectionForm::CHOICES_ORDER_ITEM => [],
        ]);


    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addAddressForm($builder)
            ->addOrderItemsForm($builder)
            ->addShipmentMethodField($builder)
            ->addDeliveryDateField($builder)
        ;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    public function addDeliveryAddressField(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_DELIVERY_ADDRESS,
            RadioType::class, [
                'constraints' => [
                    new Blank(),
                ],
            ],
            []
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addAddressForm(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FORM_ADDRESS,
            AddressForm::class,
            [
                AddressForm::OPTION_SALUTATION_CHOICES => $builder->getOption(AddressForm::OPTION_SALUTATION_CHOICES),
                AddressForm::OPTION_COUNTRY_CHOICES=> $builder->getOption(AddressForm::OPTION_COUNTRY_CHOICES),
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $choices
     *
     * @return $this
     */
    protected function addOrderItemsForm(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FORM_ORDER_ITEMS,
            OrderItemCollectionForm::class,
            [
                OrderItemCollectionForm::CHOICES_ORDER_ITEM => $builder->getOption(OrderItemCollectionForm::CHOICES_ORDER_ITEM),
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    public function addShipmentMethodField(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_SHIPMENT_METHOD,
            ChoiceType::class,
            [
                'constraints' => [
                    new NotBlank(),
                ],
                'choices' => $builder->getOption(self::OPTION_SHIPMENT_METHOD),
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    public function addDeliveryDateField(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_SHIPMENT_DATE,
            TextType::class,
            [
                'constraints' => [
                ],
            ]
        );

        return $this;
    }
}
