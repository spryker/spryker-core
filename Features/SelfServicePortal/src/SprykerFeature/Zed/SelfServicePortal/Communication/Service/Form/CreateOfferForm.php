<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class CreateOfferForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_STORE_CHOICES = 'OPTION_STORE_CHOICES';

    /**
     * @var string
     */
    public const OPTION_SHIPMENT_TYPE_CHOICES = 'OPTION_SHIPMENT_TYPE_CHOICES';

    /**
     * @var string
     */
    public const OPTION_SERVICE_POINT_CHOICES = 'OPTION_SERVICE_POINT_CHOICES';

    /**
     * @var string
     */
    public const OPTION_SERVICE_POINT_SERVICE_CHOICES = 'OPTION_SERVICE_POINT_SERVICE_CHOICES';

    /**
     * @var string
     */
    public const OPTION_EVENT_SUBSCRIBERS = 'OPTION_EVENT_SUBSCRIBERS';

    /**
     * @var string
     */
    public const OPTION_FORM_DATA_TRANSFORMERS = 'OPTION_FORM_DATA_TRANSFORMERS';

    /**
     * @uses \Symfony\Component\EventDispatcher\EventSubscriberInterface[]
     *
     * @var string
     */
    public const OPTION_FORM_EVENT_SUBSCRIBERS = 'form_event_subscribers';

    /**
     * @uses \Generated\Shared\Transfer\ProductOfferTransfer::STORES
     *
     * @var string
     */
    public const FIELD_STORES = 'stores';

    /**
     * @uses \Generated\Shared\Transfer\ProductOfferTransfer::SHIPMENT_TYPES
     *
     * @var string
     */
    public const FIELD_SHIPMENT_TYPES = 'shipmentTypes';

    /**
     * @var string
     */
    public const FIELD_SERVICE_POINT_SERVICES = 'servicePointServices';

    /**
     * @uses \Generated\Shared\Transfer\ProductOfferTransfer::IS_ACTIVE
     *
     * @var string
     */
    protected const FIELD_IS_ACTIVE = 'isActive';

    /**
     * @uses \Generated\Shared\Transfer\ProductOfferValidityTransfer::VALID_FROM
     *
     * @var string
     */
    public const FIELD_VALID_FROM = 'validFrom';

    /**
     * @uses \Generated\Shared\Transfer\ProductOfferValidityTransfer::VALID_TO
     *
     * @var string
     */
    public const FIELD_VALID_TO = 'validTo';

    /**
     * @var string
     */
    public const FIELD_SERVICE_POINT = 'servicePoint';

    /**
     * @uses \Generated\Shared\Transfer\ProductOfferStockTransfer::QUANTITY
     *
     * @var string
     */
    public const FIELD_STOCK_QUANTITY = 'stockQuantity';

    /**
     * @uses \Generated\Shared\Transfer\ProductOfferStockTransfer::IS_NEVER_OUT_OF_STOCK
     *
     * @var string
     */
    public const FIELD_IS_NEVER_OUT_OF_STOCK = 'isNeverOutOfStock';

    /**
     * @var string
     */
    protected const WIDGET_SINGLE_TEXT = 'single_text';

    /**
     * @var int
     */
    protected const FIELD_MERCHANT_SKU_MAX_LENGTH = 255;

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addIsActiveField($builder, $options)
            ->addStoresField($builder, $options)
            ->addStockQuantityField($builder, $options)
            ->addIsNeverOutOfStockField($builder, $options)
            ->addValidFromField($builder, $options)
            ->addValidToField($builder, $options)
            ->addServicePointField($builder, $options)
            ->addServicePointServicesField($builder, $options)
            ->addShipmentTypesField($builder, $options);

        $this->addEventSubscribers($builder, $options[static::OPTION_FORM_EVENT_SUBSCRIBERS]);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired([
            static::OPTION_STORE_CHOICES,
            static::OPTION_SHIPMENT_TYPE_CHOICES,
            static::OPTION_SERVICE_POINT_CHOICES,
            static::OPTION_SERVICE_POINT_SERVICE_CHOICES,
            static::OPTION_FORM_DATA_TRANSFORMERS,
            static::OPTION_FORM_EVENT_SUBSCRIBERS,
        ]);

        $resolver->setDefaults([
            static::OPTION_STORE_CHOICES => [],
            static::OPTION_SHIPMENT_TYPE_CHOICES => [],
            static::OPTION_SERVICE_POINT_CHOICES => [],
            static::OPTION_SERVICE_POINT_SERVICE_CHOICES => [],
            static::OPTION_FORM_DATA_TRANSFORMERS => [],
            static::OPTION_EVENT_SUBSCRIBERS => [],
            static::OPTION_FORM_EVENT_SUBSCRIBERS => [],
            'data_class' => ProductOfferTransfer::class,

        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    public function addIsActiveField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_IS_ACTIVE,
            Select2ComboBoxType::class,
            [
                'label' => 'Is Active',
                'required' => true,
                'data' => 1,
                'choices' => [
                    'Yes' => 1,
                    'No' => 0,
                ],
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addStoresField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_STORES,
            Select2ComboBoxType::class,
            [
                'choices' => $options[static::OPTION_STORE_CHOICES],
                'multiple' => true,
                'label' => 'Stores',
                'required' => true,
                'empty_data' => [],
                'attr' => [
                    'placeholder' => 'Select stores',
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ],
        );

        $builder->get(static::FIELD_STORES)->addModelTransformer($options[static::OPTION_FORM_DATA_TRANSFORMERS][static::FIELD_STORES]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addShipmentTypesField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_SHIPMENT_TYPES,
            Select2ComboBoxType::class,
            [
                'choices' => $options[static::OPTION_SHIPMENT_TYPE_CHOICES],
                'multiple' => true,
                'label' => 'Shipment Types',
                'required' => false,
                'empty_data' => [],
                'attr' => [
                    'placeholder' => 'Select shipment types',
                ],
            ],
        );

        $builder->get(static::FIELD_SHIPMENT_TYPES)->addModelTransformer($options[static::OPTION_FORM_DATA_TRANSFORMERS][static::FIELD_SHIPMENT_TYPES]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addValidFromField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_VALID_FROM, DateType::class, [
            'required' => false,
            'attr' => [
                'class' => 'js-from-date',
            ],
            'label' => 'Valid From',
            'constraints' => [
                $this->createValidFromRangeConstraint(),
            ],
            'widget' => static::WIDGET_SINGLE_TEXT,
            'property_path' => 'productOfferValidity.validFrom',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addValidToField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_VALID_TO, DateType::class, [
            'required' => false,
            'attr' => [
                'class' => 'js-from-date',
            ],
            'label' => 'Valid To',
            'constraints' => [
                $this->createValidToFieldRangeConstraint(),
            ],
            'widget' => static::WIDGET_SINGLE_TEXT,
            'property_path' => 'productOfferValidity.validTo',
        ]);

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\Callback
     */
    protected function createValidFromRangeConstraint(): Callback
    {
        return new Callback([
            'callback' => function ($validFrom, ExecutionContextInterface $context): void {
                $formData = $context->getRoot()->getData();
                if (!$validFrom) {
                    return;
                }

                if ($formData->getproductOfferValidity()->getValidTo()) {
                    if ($validFrom > $formData->getproductOfferValidity()->getValidTo()) {
                        $context->addViolation('Date "Valid from" cannot be later than "Valid to".');
                    }

                    if ($validFrom == $formData->getproductOfferValidity()->getValidTo()) {
                        $context->addViolation('Date "Valid from" is the same as "Valid to".');
                    }
                }
            },
        ]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\Callback
     */
    protected function createValidToFieldRangeConstraint(): Callback
    {
        return new Callback([
            'callback' => function ($validTo, ExecutionContextInterface $context): void {
                $formData = $context->getRoot()->getData();
                if (!$validTo) {
                    return;
                }

                if ($formData->getproductOfferValidity()->getValidFrom()) {
                    if ($validTo < $formData->getproductOfferValidity()->getValidFrom()) {
                        $context->addViolation('Date "Valid to" cannot be earlier than "Valid from".');
                    }
                }
            },
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addServicePointField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_SERVICE_POINT, ChoiceType::class, [
            'label' => 'Service Point',
            'choices' => $options[static::OPTION_SERVICE_POINT_CHOICES],
            'placeholder' => 'Select a service point',
            'expanded' => false,
            'multiple' => false,
            'mapped' => false,
            'required' => false,
            'attr' => [
                'data-disable-placeholder' => true,
                'data-dependent-name' => 'id-service-point',
                'class' => 'js-select-dependable js-select-dependable--service-point spryker-form-select2combobox',
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addServicePointServicesField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_SERVICE_POINT_SERVICES, ChoiceType::class, [
            'label' => 'Services',
            'choices' => $options[static::OPTION_SERVICE_POINT_SERVICE_CHOICES],
            'placeholder' => 'Select a service',
            'expanded' => false,
            'multiple' => true,
            'required' => false,
            'constraints' => [
                $this->createServicePointServicesValidationConstraint(),
            ],
            'attr' => [
                'data-dependent-preload-url' => '/self-service-portal/create-offer/service-choices?',
                'data-clear-initial' => true,
                'data-dependent-disable-when-empty' => true,
                'data-depends-on-field' => '.js-select-dependable--service-point',
                'class' => 'js-select-dependable js-select-dependable--service-point-services spryker-form-select2combobox',
            ],
            'property_path' => 'services',
        ]);

        $builder->get(static::FIELD_SERVICE_POINT_SERVICES)->addModelTransformer($options[static::OPTION_FORM_DATA_TRANSFORMERS][static::FIELD_SERVICE_POINT_SERVICES]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addStockQuantityField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_STOCK_QUANTITY,
            IntegerType::class,
            [
                'label' => 'Quantity',
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new NotBlank(),
                    new PositiveOrZero(),
                ],
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addIsNeverOutOfStockField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_IS_NEVER_OUT_OF_STOCK,
            CheckboxType::class,
            [
                'required' => false,
                'mapped' => false,
                'label' => 'Is Never Out Of Stock',
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param list<\Symfony\Component\EventDispatcher\EventSubscriberInterface> $eventSubscribers
     *
     * @return $this
     */
    protected function addEventSubscribers(FormBuilderInterface $builder, array $eventSubscribers)
    {
        foreach ($eventSubscribers as $subscriber) {
            $builder->addEventSubscriber($subscriber);
        }

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\Callback
     */
    protected function createServicePointServicesValidationConstraint(): Callback
    {
        return new Callback([
            'callback' => function ($value, ExecutionContextInterface $context): void {
                $servicePointField = $context->getRoot()->get(static::FIELD_SERVICE_POINT);
                $servicePointValue = $servicePointField->getData();

                if ($servicePointValue !== null && $servicePointValue !== '') {
                    if (count($value) === 0) {
                        $context->addViolation('Services are required when a service point is selected.');
                    }
                }
            },
        ]);
    }
}
