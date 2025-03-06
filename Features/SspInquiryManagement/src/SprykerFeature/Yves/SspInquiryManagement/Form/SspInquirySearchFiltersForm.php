<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspInquiryManagement\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerFeature\Yves\SspInquiryManagement\SspInquiryManagementConfig getConfig()
 */
class SspInquirySearchFiltersForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_DATE_FROM = 'dateFrom';

    /**
     * @var string
     */
    public const FIELD_DATE_TO = 'dateTo';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired([
            SspInquirySearchForm::OPTION_CURRENT_TIMEZONE,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addDateFromField($builder, $options)
            ->addDateToField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addDateFromField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_DATE_FROM, DateType::class, [
            'widget' => 'single_text',
            'required' => false,
            'view_timezone' => $options[SspInquirySearchForm::OPTION_CURRENT_TIMEZONE],
            'label' => 'customer.ssp_inquiries.date_from',
            'attr' => [
                'class' => 'form__field col col--sm-6',
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
    protected function addDateToField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_DATE_TO, DateType::class, [
            'widget' => 'single_text',
            'required' => false,
            'view_timezone' => $options[SspInquirySearchForm::OPTION_CURRENT_TIMEZONE],
            'label' => 'customer.ssp_inquiries.date_to',
            'attr' => [
                'class' => 'form__field col col--sm-12 col--lg-6',
            ],
        ]);

        return $this;
    }
}
