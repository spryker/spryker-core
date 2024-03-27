<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\MerchantRelationRequestGui\MerchantRelationRequestGuiConfig getConfig()
 * @method \Spryker\Zed\MerchantRelationRequestGui\Communication\MerchantRelationRequestGuiCommunicationFactory getFactory()
 */
class MerchantRelationRequestListTableFiltersForm extends AbstractType
{
    /**
     * @var string
     */
    public const OPTION_COMPANIES = 'companies';

    /**
     * @var string
     */
    public const OPTION_MERCHANTS = 'merchants';

    /**
     * @var string
     */
    public const FIELD_COMPANY = 'idCompany';

    /**
     * @var string
     */
    public const FIELD_MERCHANT = 'idMerchant';

    /**
     * @var string
     */
    protected const LABEL_COMPANY = 'Company';

    /**
     * @var string
     */
    protected const LABEL_MERCHANT = 'Merchant';

    /**
     * @var string
     */
    protected const PLACEHOLDER_COMPANY = 'Select Company';

    /**
     * @var string
     */
    protected const PLACEHOLDER_MERCHANT = 'Select Merchant';

    /**
     * @var string
     */
    protected const FORM_METHOD = 'GET';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return '';
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
            static::OPTION_COMPANIES,
            static::OPTION_MERCHANTS,
        ]);

        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCompanyField($builder, $options)
            ->addMerchantField($builder, $options);

        $builder->setMethod(static::FORM_METHOD);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<mixed> $options
     *
     * @return $this
     */
    protected function addCompanyField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_COMPANY, ChoiceType::class, [
            'choices' => $options[static::OPTION_COMPANIES],
            'label' => static::LABEL_COMPANY,
            'required' => false,
            'placeholder' => static::PLACEHOLDER_COMPANY,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @param array<mixed> $options
     *
     * @return $this
     */
    protected function addMerchantField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_MERCHANT, ChoiceType::class, [
            'choices' => $options[static::OPTION_MERCHANTS],
            'label' => static::LABEL_MERCHANT,
            'required' => false,
            'placeholder' => static::PLACEHOLDER_MERCHANT,
        ]);

        return $this;
    }
}
