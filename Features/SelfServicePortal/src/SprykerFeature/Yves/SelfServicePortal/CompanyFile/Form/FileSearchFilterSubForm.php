<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\CompanyFile\Form;

use DateTime;
use Spryker\Yves\Kernel\Form\AbstractType;
use SprykerFeature\Yves\SelfServicePortal\CompanyFile\Form\DataProvider\FileSearchFilterFormDataProvider;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
    public const FIELD_BUSINESS_ENTITY = 'businessEntity';

    /**
     * @var string
     */
    public const FIELD_SSP_ASSET_ENTITY = 'sspAssetEntity';

    /**
     * @var string
     */
    public const FIELD_SEARCH = 'search';

    /**
     * @var string
     */
    protected const DATE_TIME_FORMAT = 'Y-m-d\TH:i';

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired([
            FileSearchFilterForm::OPTION_FILE_TYPES,
            FileSearchFilterForm::OPTION_BUSINESS_ENTITIES,
            FileSearchFilterForm::OPTION_SSP_ASSET_ENTITIES,
        ]);

        $resolver->setDefined([
            FileSearchFilterForm::OPTION_DEFAULT_BUSINESS_ENTITY,
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
            ->addBusinessEntityField($builder, $options)
            ->addSspAssetEntityField($builder, $options)
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
        $builder->add(static::FIELD_DATE_FROM, DateType::class, [
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
        $builder->add(static::FIELD_DATE_TO, DateType::class, [
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
    protected function addBusinessEntityField(FormBuilderInterface $builder, array $options)
    {
        $defaultValue = $options[FileSearchFilterForm::OPTION_DEFAULT_BUSINESS_ENTITY] ?? FileSearchFilterFormDataProvider::FILE_ATTACHMENT_TYPE_ALL;

        $builder->add(static::FIELD_BUSINESS_ENTITY, ChoiceType::class, [
            'choices' => array_flip($options[FileSearchFilterForm::OPTION_BUSINESS_ENTITIES]),
            'required' => false,
            'placeholder' => false,
            'label' => 'self_service_portal.company_file.file_search_filter_form.field.business_entity.label',
            'data' => $defaultValue,
            'attr' => [
                'data-qa' => 'filter-business-entity',
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
    protected function addSspAssetEntityField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_SSP_ASSET_ENTITY, ChoiceType::class, [
            'choices' => array_flip($options[FileSearchFilterForm::OPTION_SSP_ASSET_ENTITIES]),
            'required' => false,
            'placeholder' => false,
            'label' => 'self_service_portal.company_file.file_search_filter_form.field.ssp_asset_entity.label',
            'data' => FileSearchFilterFormDataProvider::FILE_ATTACHMENT_TYPE_ALL,
            'attr' => [
                'data-qa' => 'filter-ssp-asset-entity',
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
            'label' => 'self_service_portal.company_file.file_search_filter_form.field.search.label',
            'attr' => [
                'data-qa' => 'search',
            ],
        ]);

        return $this;
    }

    protected function formatDateString(): callable
    {
        return fn ($date) => DateTime::createFromFormat(static::DATE_TIME_FORMAT, $date) ?: null;
    }

    protected function formatDateTime(): callable
    {
        return fn ($date) => $date ? $date->format(static::DATE_TIME_FORMAT) : null;
    }
}
