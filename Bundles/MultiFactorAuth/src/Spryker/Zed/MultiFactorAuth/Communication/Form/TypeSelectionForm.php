<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Communication\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\MultiFactorAuth\MultiFactorAuthConfig getConfig()
 * @method \Spryker\Zed\MultiFactorAuth\Persistence\MultiFactorAuthRepositoryInterface getRepository()
 * @method \Spryker\Zed\MultiFactorAuth\Business\MultiFactorAuthFacadeInterface getFacade()
 * @method \Spryker\Zed\MultiFactorAuth\Communication\MultiFactorAuthCommunicationFactory getFactory()
 */
class TypeSelectionForm extends BaseMultiFactorAuthForm
{
    /**
     * @var string
     */
    protected const FIELD_TYPE = 'type';

    /**
     * @uses \Spryker\Zed\MultiFactorAuth\Communication\Form\DataProvider\TypeSelectionFormDataProvider::OPTION_TYPES
     *
     * @var string
     */
    protected const OPTION_TYPES = 'types';

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
            array_map('ucfirst', $options[static::OPTION_TYPES]),
            $options[static::OPTION_TYPES],
        );

        $builder->add(static::FIELD_TYPE, ChoiceType::class, [
            'choices' => $mappedOptions,
            'expanded' => true,
            'multiple' => false,
            'label' => false,
        ]);

        return $this;
    }
}
