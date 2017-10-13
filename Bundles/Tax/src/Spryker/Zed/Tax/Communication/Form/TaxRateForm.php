<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication\Form;

use Spryker\Zed\Tax\Communication\Form\DataProvider\TaxRateFormDataProvider;
use Spryker\Zed\Tax\Communication\Form\Transform\PercentageTransformer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class TaxRateForm extends AbstractType
{
    const FIELD_NAME = 'name';
    const FIELD_RATE = 'rate';
    const FIELD_COUNTRY = 'fkCountry';
    const FIELD_ID_TAX_RATE = 'idTaxRate';

    /**
     * @var \Spryker\Zed\Tax\Communication\Form\DataProvider\TaxRateFormDataProvider
     */
    protected $taxRateFormDataProvider;

    /**
     * @var \Spryker\Zed\Tax\Communication\Form\Transform\PercentageTransformer
     */
    protected $percentageTransformer;

    /**
     * @param \Spryker\Zed\Tax\Communication\Form\DataProvider\TaxRateFormDataProvider $taxRateFormDataProvider
     * @param \Spryker\Zed\Tax\Communication\Form\Transform\PercentageTransformer $percentageTransformer
     */
    public function __construct(
        TaxRateFormDataProvider $taxRateFormDataProvider,
        PercentageTransformer $percentageTransformer
    ) {
        $this->taxRateFormDataProvider = $taxRateFormDataProvider;
        $this->percentageTransformer = $percentageTransformer;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param string[] $options
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
                'label' => 'Name',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ],
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
            'choices' => $this->taxRateFormDataProvider->getOptions()[self::FIELD_COUNTRY],
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
     *
     * @return $this
     */
    protected function addPercentage(FormBuilderInterface $builder)
    {
        $builder->add(
            self::FIELD_RATE,
            'text',
            [
                'label' => 'Percentage',
                'required' => true,
                'constraints' => [
                    new Range([
                        'min' => 0,
                        'max' => 100,
                    ]),
                ],
            ]
        );

        $builder->get(self::FIELD_RATE)
            ->addModelTransformer($this->percentageTransformer);

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
