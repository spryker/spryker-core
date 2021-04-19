<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\SkuRegexConstraint;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\UniqueAbstractSkuConstraint;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateProductAbstractWithMultiConcreteForm extends AbstractType
{
    public const FIELD_NAME = 'name';
    public const FIELD_SKU = 'sku';
    public const FIELD_CONCRETE_PRODUCTS = 'concreteProducts';

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param mixed[] $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addSkuField($builder)
            ->addNameField($builder)
            ->addConcreteProductsField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSkuField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SKU, HiddenType::class, [
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new SkuRegexConstraint(),
                new UniqueAbstractSkuConstraint(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NAME, HiddenType::class, [
            'required' => true,
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addConcreteProductsField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_CONCRETE_PRODUCTS, CollectionType::class, [
            'required' => true,
            'entry_type' => ProductConcreteForm::class,
        ]);

        $builder->get(self::FIELD_CONCRETE_PRODUCTS)
            ->addModelTransformer($this->createJsonTransformer());

        return $this;
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createJsonTransformer(): CallbackTransformer
    {
        return new CallbackTransformer(
            function ($value) {
                return $value ? json_decode($value, true) : [];
            },
            function ($value) {
                return $value ? json_encode($value) : '';
            }
        );
    }
}
