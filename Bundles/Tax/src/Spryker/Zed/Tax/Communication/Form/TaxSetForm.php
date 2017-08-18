<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication\Form;

use ArrayObject;
use Spryker\Zed\Tax\Communication\Form\DataProvider\TaxSetFormDataProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class TaxSetForm extends AbstractType
{

    const FIELD_NAME = 'name';
    const FIELD_TAX_RATES = 'taxRates';
    const FIELD_ID_TAX_SET = 'idTaxSet';

    /**
     * @var \Spryker\Zed\Tax\Communication\Form\DataProvider\TaxSetFormDataProvider
     */
    protected $taxSetFormDataProvider;

    /**
     * @param \Spryker\Zed\Tax\Communication\Form\DataProvider\TaxSetFormDataProvider $taxSetFormDataProvider
     */
    public function __construct(TaxSetFormDataProvider $taxSetFormDataProvider)
    {
        $this->taxSetFormDataProvider = $taxSetFormDataProvider;
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
            ->addTaxRates($builder);
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
                    new NotBlank()
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
    protected function addTaxRates(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_TAX_RATES, 'choice', [
            'expanded' => true,
            'multiple' => true,
            'label' => 'Tax rates',
            'choice_list' => $this->taxSetFormDataProvider->getOptions()[self::FIELD_TAX_RATES],
            'constraints' => [
                new Callback([
                    'methods' => [
                        function (ArrayObject $taxRates, ExecutionContextInterface $context) {
                            if ($taxRates->count() <= 0) {
                                $context->addViolation('You should choose one or more tax rates');
                            }
                        },
                    ],
                ]),
            ],
        ]);

        $builder->get(self::FIELD_TAX_RATES)
            ->addModelTransformer(new CallbackTransformer(
                function ($taxRates) {
                    if ($taxRates) {
                        return (array)$taxRates;
                    }
                },
                function ($taxRates) {
                    return new ArrayObject($taxRates);
                }
            ));

        return $this;
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'tax_set';
    }

}
