<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Form;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 * @method \Spryker\Zed\MultiFactorAuth\Communication\MultiFactorAuthCommunicationFactory getFactory()
 * @method \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface getRepository()
 * @method \Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface getFacade()
 */
class CodeValidationForm extends BaseMultiFactorAuthForm
{
    /**
     * @uses \Spryker\Zed\MultiFactorAuth\Communication\Form\DataProvider\TypeSelectionDataProvider::OPTION_TYPES
     *
     * @var string
     */
    protected const OPTION_TYPES = 'types';

    /**
     * @var string
     */
    protected const FIELD_TYPE = 'type';

    /**
     * @var string
     */
    protected const FIELD_AUTHENTICATION_CODE = 'authentication_code';

    /**
     * @var string
     */
    protected const PARAM_TYPE = '%s';

    /**
     * @var string
     */
    protected const ENTER_CODE_LABEL_PLACEHOLDER = 'We sent the authentication code to your %s. Type it below to continue.';

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
            static::OPTION_TYPES => [],
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

        $this->addTypeHiddenField($builder, $options)
            ->addAuthenticationCodeField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addTypeHiddenField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_TYPE, HiddenType::class, [
            'data' => $options[static::OPTION_TYPES][0] ?? '',
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
        $builder->add(static::FIELD_AUTHENTICATION_CODE, NumberType::class, [
            'label' => $this->getFactory()->getTranslatorService()->trans(static::ENTER_CODE_LABEL_PLACEHOLDER, [
                static::PARAM_TYPE => $options[static::OPTION_TYPES][0] ?? '',
            ]),
            'attr' => [
                'placeholder' => 'Enter code',
                'autocomplete' => 'one-time-code',
                'inputmode' => 'numeric',
                'maxlength' => $this->getConfig()->getUserCodeLength(),
                'oninput' => sprintf('this.value = this.value.replace(/\\D/g, \'\').slice(0, %d);', $this->getConfig()->getUserCodeLength()),
            ],
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }
}
