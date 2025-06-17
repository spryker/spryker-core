<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\CompanyFile\Form;

use DateTime;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class FileSearchFilterSubForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_TYPE = 'type';

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
    protected const DATE_TIME_FORMAT = 'Y-m-d\TH:i';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired([
            FileSearchFilterForm::OPTION_FILE_TYPES,
            FileSearchFilterForm::OPTION_ACCESS_LEVELS,
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
            ->addTypeField($builder, $options)
            ->addDateFromField($builder)
            ->addDateToField($builder)
            ->addAccessLevelField($builder, $options)
            ->addSearchField($builder);
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
            'choices' => array_flip($options[FileSearchFilterForm::OPTION_FILE_TYPES]),
            'required' => false,
            'placeholder' => 'self_service_portal.company_file.file_search_filter_form.field.type.placeholder',
            'label' => 'self_service_portal.company_file.file_search_filter_form.field.type.label',
            'attr' => [
                'data-qa' => 'filter-type',
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDateFromField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_DATE_FROM, DateTimeType::class, [
            'widget' => 'single_text',
            'required' => false,
            'label' => 'self_service_portal.company_file.file_search_filter_form.field.date_from.label',
        ]);

        $builder->get(static::FIELD_DATE_FROM)
            ->addModelTransformer(new CallbackTransformer($this->formatDateString(), $this->formatDateTime()));

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addDateToField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_DATE_TO, DateTimeType::class, [
            'widget' => 'single_text',
            'required' => false,
            'label' => 'self_service_portal.company_file.file_search_filter_form.field.date_to.label',
        ]);

        $builder->get(static::FIELD_DATE_TO)
            ->addModelTransformer(new CallbackTransformer($this->formatDateString(), $this->formatDateTime()));

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
        $builder->add(static::FIELD_ACCESS_LEVEL, ChoiceType::class, [
            'choices' => array_flip($options[FileSearchFilterForm::OPTION_ACCESS_LEVELS]),
            'required' => false,
            'placeholder' => 'self_service_portal.company_file.file_search_filter_form.field.access_level.placeholder',
            'label' => 'self_service_portal.company_file.file_search_filter_form.field.access_level.label',
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
            'label' => 'self_service_portal.company_file.file_search_filter_form.field.search.label',
            'attr' => [
                'data-qa' => 'search',
            ],
        ]);

        return $this;
    }

    /**
     * @return callable
     */
    protected function formatDateString(): callable
    {
        return fn ($date) => DateTime::createFromFormat(static::DATE_TIME_FORMAT, $date) ?: null;
    }

    /**
     * @return callable
     */
    protected function formatDateTime(): callable
    {
        return fn ($date) => $date ? $date->format(static::DATE_TIME_FORMAT) : null;
    }
}
