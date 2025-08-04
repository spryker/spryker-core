<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Form;

use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Spryker\Yves\MultiFactorAuth\Controller\CustomerMultiFactorAuthFlowController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Yves\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 */
class TypeSelectionForm extends BaseMultiFactorAuthForm
{
    /**
     * @var string
     */
    protected const GLOSSARY_MULTI_FACTOR_AUTH_REQUIRED_OPTIONS = 'multi_factor_auth.required_options';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'typeSelectionForm';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            CustomerMultiFactorAuthFlowController::TYPES => [],
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
        parent::buildForm($builder, $options);

        $this->addSelectedMethodField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addSelectedMethodField(FormBuilderInterface $builder, array $options)
    {
        $mappedOptions = array_combine(
            array_map('ucfirst', $options[CustomerMultiFactorAuthFlowController::TYPES]),
            $options[CustomerMultiFactorAuthFlowController::TYPES],
        );

        $builder->add(MultiFactorAuthTransfer::TYPE, ChoiceType::class, [
            'choices' => $mappedOptions,
            'expanded' => true,
            'multiple' => false,
            'label' => static::GLOSSARY_MULTI_FACTOR_AUTH_REQUIRED_OPTIONS,
            'required' => true,
            'placeholder' => false,
        ]);

        return $this;
    }
}
