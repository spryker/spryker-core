<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerFeature\Yves\SspInquiryManagement\SspInquiryManagementConfig getConfig()
 */
class SspInquirySearchForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_TYPE = 'type';

    /**
     * @var string
     */
    public const FIELD_STATUS = 'status';

    /**
     * @var string
     */
    public const FIELD_ORDER_BY = 'orderBy';

    /**
     * @var string
     */
    public const FIELD_ORDER_DIRECTION = 'orderDirection';

    /**
     * @var string
     */
    public const FIELD_FILTERS = 'filters';

    /**
     * @var string
     */
    public const OPTION_SSP_INQUIRY_TYPES = 'OPTION_SSP_INQUIRY_TYPES';

    /**
     * @var string
     */
    public const OPTION_SSP_INQUIRY_STATUSES = 'OPTION_SSP_INQUIRY_STATUSES';

    /**
     * @var string
     */
    public const OPTION_CURRENT_TIMEZONE = 'OPTION_CURRENT_TIMEZONE';

    /**
     * @var string
     */
    public const FORM_NAME = 'sspInquirySearchForm';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired([
            static::OPTION_SSP_INQUIRY_TYPES,
            static::OPTION_CURRENT_TIMEZONE,
            static::OPTION_SSP_INQUIRY_STATUSES,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::FORM_NAME;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setMethod(Request::METHOD_GET);

        $this->addTypeField($builder, $options)
            ->addStatusField($builder, $options)
            ->addFiltersForm($builder, $options)
            ->addOrderByField($builder)
            ->addOrderDirectionField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addTypeField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_TYPE, ChoiceType::class, [
            'choices' => $options[static::OPTION_SSP_INQUIRY_TYPES],
            'placeholder' => 'ssp_inquiry.list.filter.type.placeholder',
            'required' => false,
            'label' => 'ssp_inquiry.list.filter.type.label',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addStatusField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_STATUS, ChoiceType::class, [
            'choices' => $options[static::OPTION_SSP_INQUIRY_STATUSES],
            'placeholder' => 'ssp_inquiry.list.filter.status.placeholder',
            'required' => false,
            'label' => 'ssp_inquiry.list.filter.status.label',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addOrderByField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ORDER_BY, HiddenType::class, [
            'required' => false,
            'label' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addOrderDirectionField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ORDER_DIRECTION, HiddenType::class, [
            'required' => false,
            'label' => false,
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addFiltersForm(FormBuilderInterface $builder, array $options)
    {
        $options = [
            static::OPTION_CURRENT_TIMEZONE => $options[static::OPTION_CURRENT_TIMEZONE],
        ];

        $builder->add(
            static::FIELD_FILTERS,
            SspInquirySearchFiltersForm::class,
            $options,
        );

        return $this;
    }
}
