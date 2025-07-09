<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\CompanyFile\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class FileSearchFilterForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_FILE_TYPES = 'option_file_types';

    /**
     * @var string
     */
    public const OPTION_BUSINESS_ENTITIES = 'option_business_entity_types';

    /**
     * @var string
     */
    public const OPTION_SSP_ASSET_ENTITIES = 'option_ssp_asset_entity_types';

    /**
     * @var string
     */
    public const FIELD_RESET = 'reset';

    /**
     * @var string
     */
    public const FIELD_FILTERS = 'filters';

    /**
     * @var string
     */
    public const FORM_NAME = 'fileSearchFilterForm';

    /**
     * @var string
     */
    public const FIELD_ORDER_BY = 'orderBy';

    /**
     * @var string
     */
    public const FIELD_ORDER_DIRECTION = 'orderDirection';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired([
            static::OPTION_FILE_TYPES,
            static::OPTION_BUSINESS_ENTITIES,
            static::OPTION_SSP_ASSET_ENTITIES,
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

        $this->addResetField($builder)
            ->addOrderByField($builder)
            ->addOrderDirectionField($builder)
            ->addFiltersField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addResetField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_RESET, HiddenType::class, [
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
    protected function addFiltersField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_FILTERS,
            FileSearchFilterSubForm::class,
            [
                static::OPTION_FILE_TYPES => $options[static::OPTION_FILE_TYPES],
                static::OPTION_BUSINESS_ENTITIES => $options[static::OPTION_BUSINESS_ENTITIES],
                static::OPTION_SSP_ASSET_ENTITIES => $options[static::OPTION_SSP_ASSET_ENTITIES],
            ],
        );

        return $this;
    }
}
