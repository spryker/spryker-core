<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Form;

use Generated\Shared\Transfer\MultiFactorAuthTransfer;
use Spryker\Yves\MultiFactorAuth\Controller\CustomerMultiFactorAuthFlowController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Yves\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 * @method \Spryker\Yves\MultiFactorAuth\MultiFactorAuthFactory getFactory()
 */
class CodeValidationForm extends BaseMultiFactorAuthForm
{
    /**
     * @var string
     */
    protected const ENTER_CODE_LABEL_PLACEHOLDER = 'multi_factor_auth.enter_code_for_method';

    /**
     * @var string
     */
    protected const ENTER_CODE_PLACEHOLDER = 'multi_factor_auth.enter_your_code_input';

    /**
     * @var string
     */
    protected const PARAM_TYPE = '%type%';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'codeValidationForm';
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
            CustomerMultiFactorAuthFlowController::CODE_LENGTH => null,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options = []): void
    {
        parent::buildForm($builder, $options);

        $this->addTypeHiddenField($builder, $options)
            ->addAuthenticationCodeField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addTypeHiddenField(FormBuilderInterface $builder, array $options = [])
    {
        $builder->add(MultiFactorAuthTransfer::TYPE, HiddenType::class, [
            'data' => $options[CustomerMultiFactorAuthFlowController::TYPES][0] ?? '',
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addAuthenticationCodeField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(CustomerMultiFactorAuthFlowController::AUTHENTICATION_CODE, NumberType::class, [
            'label' => $this->getFactory()->getTranslatorService()->trans(static::ENTER_CODE_LABEL_PLACEHOLDER, [
                static::PARAM_TYPE => $options[CustomerMultiFactorAuthFlowController::TYPES][0] ?? '',
            ]),
            'attr' => [
                'placeholder' => $this->getFactory()->getTranslatorService()->trans(static::ENTER_CODE_PLACEHOLDER),
                'autocomplete' => 'one-time-code',
                'inputmode' => 'numeric',
                'maxlength' => $options[CustomerMultiFactorAuthFlowController::CODE_LENGTH],
                'oninput' => sprintf('this.value = this.value.replace(/\\D/g, \'\').slice(0, %d);', $options[CustomerMultiFactorAuthFlowController::CODE_LENGTH]),
            ],
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }
}
