<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspServiceManagement\Communication\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerFeature\Zed\SspServiceManagement\Communication\SspServiceManagementCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SspServiceManagement\SspServiceManagementConfig getConfig()
 * @method \SprykerFeature\Zed\SspServiceManagement\Persistence\SspServiceManagementRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SspServiceManagement\Business\SspServiceManagementFacadeInterface getFacade()
 */
class EditOfferForm extends CreateOfferForm
{
    /**
     * @var string
     */
    protected const FIELD_ID_PRODUCT_OFFER = 'idProductOffer';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->add(static::FIELD_ID_PRODUCT_OFFER, HiddenType::class);
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
                new NotBlank(),
            ],
            'attr' => [
                'data-dependent-preload-url' => '/ssp-service-management/create-offer/service-choices?',
                'data-clear-initial' => false,
                'data-dependent-disable-when-empty' => true,
                'data-depends-on-field' => '.js-select-dependable--service-point',
                'class' => 'js-select-dependable js-select-dependable--service-point-services spryker-form-select2combobox',
            ],
            'mapped' => false,
        ]);

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

        $builder->get(static::FIELD_VALID_FROM)->addModelTransformer($options[static::OPTION_FORM_DATA_TRANSFORMERS][static::FIELD_VALID_FROM]);

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

        $builder->get(static::FIELD_VALID_TO)->addModelTransformer($options[static::OPTION_FORM_DATA_TRANSFORMERS][static::FIELD_VALID_TO]);

        return $this;
    }
}
