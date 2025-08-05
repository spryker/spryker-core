<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Asset\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class SspAssetSearchFiltersForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_SCOPE = 'scope';

    /**
     * @var string
     */
    public const SCOPE_OPTIONS = 'scope_options';

    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired([
            static::SCOPE_OPTIONS,
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
        $this->addScopeField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addScopeField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_SCOPE, ChoiceType::class, [
            'required' => false,
            'choices' => $options[static::SCOPE_OPTIONS],
            'label' => 'customer.ssp_asset.filter.scope',
            'placeholder' => 'customer.ssp_asset.filter.scope.placeholder',
            'attr' => [
                'data-qa' => 'ssp-asset-filter-scope',
            ],
        ]);

        return $this;
    }
}
