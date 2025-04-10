<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\MultiFactorAuth\Form;

use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
     * @uses \Spryker\Yves\MultiFactorAuth\Form\DataProvider\TypeSelectionDataProvider::OPTION_TYPES
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
    protected const ENTER_CODE_LABEL_PLACEHOLDER = 'multi_factor_auth.enter_code_for_method';

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
            static::OPTION_TYPES => [],
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
        $builder->add(static::FIELD_AUTHENTICATION_CODE, TextType::class, [
            'label' => $this->getFactory()->getTranslatorService()->trans(static::ENTER_CODE_LABEL_PLACEHOLDER, [
                static::PARAM_TYPE => $options[static::OPTION_TYPES][0] ?? '',
            ]),
            'attr' => [
                'placeholder' => 'Enter your code here',
                'autocomplete' => 'one-time-code',
            ],
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }
}
