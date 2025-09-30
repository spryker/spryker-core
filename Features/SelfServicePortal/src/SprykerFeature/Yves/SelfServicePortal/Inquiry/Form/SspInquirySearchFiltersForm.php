<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Inquiry\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
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
     * @var string
     */
    public const FIELD_ACCESS_LEVEL = 'accessLevel';

    /**
     * @var string
     */
    public const FIELD_SEARCH = 'search';

    /**
     * @var string
     */
    public const OPTION_ACCESS_LEVELS = 'OPTION_ACCESS_LEVELS';

    /**
     * @var string
     */
    public const OPTION_DEFAULT_ACCESS_LEVEL = 'OPTION_DEFAULT_ACCESS_LEVEL';

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired([
            SspInquirySearchForm::OPTION_CURRENT_TIMEZONE,
            static::OPTION_ACCESS_LEVELS,
        ]);

        $resolver->setDefined([
            static::OPTION_DEFAULT_ACCESS_LEVEL,
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
            ->addDateToField($builder, $options)
            ->addAccessLevelField($builder, $options)
            ->addSearchField($builder);
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

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addAccessLevelField(FormBuilderInterface $builder, array $options)
    {
        $defaultValue = $options[static::OPTION_DEFAULT_ACCESS_LEVEL] ?? null;

        $builder->add(static::FIELD_ACCESS_LEVEL, ChoiceType::class, [
            'required' => false,
            'choices' => $options[static::OPTION_ACCESS_LEVELS],
            'data' => $defaultValue,
            'label' => 'customer.ssp_inquiries.access_level.label',
            'placeholder' => 'customer.ssp_inquiries.access_level.placeholder',
            'attr' => [
                'data-qa' => 'ssp-inquiry-filter-access-level',
                'class' => 'form__field col--sm-12 col--lg-6',
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSearchField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SEARCH, TextType::class, [
            'required' => false,
            'sanitize_xss' => true,
            'label' => 'customer.ssp_inquiries.search.label',
            'attr' => [
                'data-qa' => 'ssp-inquiry-search',
                'class' => 'form__field col--sm-12',
                'placeholder' => 'customer.ssp_inquiries.search.placeholder',
            ],
        ]);

        return $this;
    }
}
