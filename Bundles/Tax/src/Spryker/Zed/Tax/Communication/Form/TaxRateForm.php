<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication\Form;

use Spryker\Zed\Tax\Communication\Form\DataProvider\TaxRateFormDataProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class TaxRateForm extends AbstractType
{

    const FIELD_NAME = 'name';
    const FIELD_RATE = 'rate';
    const FIELD_COUNTRY = 'fkCountry';

    /**
     * @var \Spryker\Zed\Tax\Communication\Form\DataProvider\TaxRateFormDataProvider
     */
    protected $taxRateFormDataProvider;

    /**
     * @param \Spryker\Zed\Tax\Communication\Form\DataProvider\TaxRateFormDataProvider $taxRateFormDataProvider
     */
    public function __construct(TaxRateFormDataProvider $taxRateFormDataProvider)
    {
        $this->taxRateFormDataProvider = $taxRateFormDataProvider;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array|string[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addName($builder)
            ->addCountry($builder)
            ->addPercentage($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addName(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_NAME,
            'text',
            [
                'constraints' => [
                    new NotBlank()
                ]
            ]
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCountry(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_COUNTRY, 'choice', [
            'expanded' => false,
            'multiple' => false,
            'label' => 'Country',
            'choices' => $this->taxRateFormDataProvider->getData()[self::FIELD_COUNTRY],
            'constraints' => [
                new NotBlank()
            ],
            'attr' => []
        ]);

        return $this;

    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPercentage(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_RATE,
            'text',
            [
                'constraints' => [
                    new Range([
                        'min' => 0.1,
                        'max' => 100
                    ]),

                ]
            ]
        );

        return $this;
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tax_rate';
    }

}
