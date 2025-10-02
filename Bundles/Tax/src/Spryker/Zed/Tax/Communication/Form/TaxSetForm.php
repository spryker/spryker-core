<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Tax\Communication\Form;

use ArrayObject;
use Closure;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @method \Spryker\Zed\Tax\Business\TaxFacadeInterface getFacade()
 * @method \Spryker\Zed\Tax\Communication\TaxCommunicationFactory getFactory()
 * @method \Spryker\Zed\Tax\Persistence\TaxQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Tax\Persistence\TaxRepositoryInterface getRepository()
 * @method \Spryker\Zed\Tax\TaxConfig getConfig()
 */
class TaxSetForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_NAME = 'name';

    /**
     * @var string
     */
    public const FIELD_TAX_RATES = 'taxRates';

    /**
     * @var string
     */
    public const FIELD_ID_TAX_SET = 'idTaxSet';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
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
            static::FIELD_NAME,
            TextType::class,
            [
                'label' => 'Name',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    $this->createUniqueTaxSetNameConstraint(),
                ],
            ],
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
        $builder->add(static::FIELD_TAX_RATES, ChoiceType::class, [
            'expanded' => true,
            'multiple' => true,
            'label' => 'Tax rates',
            'choices' => $this->getFactory()->createTaxSetFormDataProvider()->getOptions()[static::FIELD_TAX_RATES],
            'choice_label' => 'name',
            'choice_value' => 'idTaxRate',
            'constraints' => [
                new Callback([
                    'callback' => function (ArrayObject $taxRates, ExecutionContextInterface $context) {
                        if ($taxRates->count() <= 0) {
                            $context->addViolation('You should choose one or more tax rates');
                        }
                    },
                ]),
            ],
        ]);

        $builder
            ->get(static::FIELD_TAX_RATES)
            ->addModelTransformer($this->createModelTransformer());

        return $this;
    }

    /**
     * @return \Symfony\Component\Form\DataTransformerInterface<\ArrayObject<int, mixed>|null, array<int, mixed>|null>
     */
    protected function createModelTransformer(): DataTransformerInterface
    {
        return new CallbackTransformer(
            $this->createTransformCallback(),
            $this->createReverseTransformCallback(),
        );
    }

    /**
     * @return \Closure
     */
    protected function createTransformCallback(): Closure
    {
        return function ($taxRates) {
            if ($taxRates) {
                return (array)$taxRates;
            }
        };
    }

    /**
     * @return \Closure
     */
    protected function createReverseTransformCallback(): Closure
    {
        return function ($taxRates) {
            return new ArrayObject($taxRates);
        };
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'tax_set';
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

    /**
     * @return \Symfony\Component\Validator\Constraint
     */
    protected function createUniqueTaxSetNameConstraint(): Constraint
    {
        return new Callback([
            'callback' => function ($name, ExecutionContextInterface $context) {
                if (!$name) {
                    return;
                }

                /** @var \Symfony\Component\Form\Form $form */
                $form = $context->getObject();
                /** @var \Generated\Shared\Transfer\TaxSetTransfer $taxSetTransfer */
                $taxSetTransfer = $form->getParent()->getData();
                $idTaxSet = $taxSetTransfer->getIdTaxSet();
                if (
                    !$idTaxSet && $this->getFacade()->taxSetWithSameNameExists($name) ||
                    $idTaxSet && $this->getFacade()->taxSetWithSameNameAndIdExists($name, $idTaxSet)
                ) {
                    $context->addViolation('Tax Set with name "%name%" already exists.', [
                        '%name%' => $name,
                    ]);
                }
            },
        ]);
    }
}
