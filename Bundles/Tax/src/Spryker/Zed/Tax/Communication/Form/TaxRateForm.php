<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\Type\FormattedNumberType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

/**
 * @method \Spryker\Zed\Tax\Business\TaxFacadeInterface getFacade()
 * @method \Spryker\Zed\Tax\Communication\TaxCommunicationFactory getFactory()
 * @method \Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Tax\TaxConfig getConfig()
 * @method \Spryker\Zed\Tax\Persistence\TaxRepositoryInterface getRepository()
 */
class TaxRateForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_NAME = 'name';

    /**
     * @var string
     */
    public const FIELD_RATE = 'rate';

    /**
     * @var string
     */
    public const FIELD_COUNTRY = 'fkCountry';

    /**
     * @var string
     */
    public const FIELD_ID_TAX_RATE = 'idTaxRate';

    /**
     * @var string
     */
    public const OPTION_COUNTRIES = 'countries';

    /**
     * @var string
     */
    public const OPTION_LOCALE = 'locale';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addName($builder)
            ->addCountry($builder, $options)
            ->addPercentage($builder, $options);
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
            static::OPTION_COUNTRIES => [],
            static::OPTION_LOCALE => null,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addName(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_NAME,
            TextType::class,
            [
                'label' => 'Name',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ],
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addCountry(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_COUNTRY, ChoiceType::class, [
            'expanded' => false,
            'multiple' => false,
            'label' => 'Country',
            'choices' => $options[static::OPTION_COUNTRIES],
            'constraints' => [
                new GreaterThan([
                    'value' => 0,
                    'message' => 'Select country.',
                ]),
            ],
            'attr' => [],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string> $options
     *
     * @return $this
     */
    protected function addPercentage(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_RATE,
            FormattedNumberType::class,
            [
                'label' => 'Percentage',
                'locale' => $options[static::OPTION_LOCALE],
                'required' => true,
                'constraints' => [
                    new Range([
                        'min' => 0,
                        'max' => 100,
                    ]),
                ],
            ],
        );

        return $this;
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'tax_rate';
    }

    /**
     * @deprecated Use {@link getBlockPrefix()} instead.
     *
     * @return string
     */
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
