<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Service\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class ServiceSearchForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_SEARCH_TYPE = 'searchType';

    /**
     * @var string
     */
    public const FIELD_SEARCH_TEXT = 'searchText';

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
    public const OPTION_SERVICE_SEARCH_TYPES = 'OPTION_SERVICE_SEARCH_TYPES';

    /**
     * @var string
     */
    public const OPTION_COMPANY_BUSINESS_UNIT_CHOICES = 'OPTION_COMPANY_BUSINESS_UNIT_CHOICES';

    /**
     * @var string
     */
    public const FORM_NAME = 'serviceSearchForm';

    /**
     * @var string
     */
    public const FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT = 'companyBusinessUnit';

    /**
     * @var string
     */
    public const FIELD_FILTERS = 'filters';

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired([
            static::OPTION_SERVICE_SEARCH_TYPES,
        ]);

        $resolver->setDefaults([
            static::OPTION_COMPANY_BUSINESS_UNIT_CHOICES => [],
        ]);
    }

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

        $this->addSearchTypeField($builder, $options)
            ->addBusinessUnitField($builder, $options)
            ->addSearchTextField($builder)
            ->addOrderByField($builder)
            ->addOrderDirectionField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addSearchTypeField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_SEARCH_TYPE, ChoiceType::class, [
            'choices' => array_flip($options[static::OPTION_SERVICE_SEARCH_TYPES]),
            'data' => key($options[static::OPTION_SERVICE_SEARCH_TYPES]),
            'placeholder' => false,
            'required' => false,
            'label' => 'self_service_portal.service.list.search_placeholder',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSearchTextField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SEARCH_TEXT, TextType::class, [
            'label' => false,
            'required' => false,
            'sanitize_xss' => true,
            'attr' => [
                'placeholder' => 'global.search',
            ],
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
    protected function addBusinessUnitField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT,
            ChoiceType::class,
            [
                    'choices' => $options[static::OPTION_COMPANY_BUSINESS_UNIT_CHOICES],
                    'required' => false,
                    'placeholder' => 'self_service_portal.service.list.my_services',
                    'label' => 'self_service_portal.service.list.field.business_unit',
                ],
        );

        return $this;
    }
}
