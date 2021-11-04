<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\SkuRegexConstraint;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\UniqueProductConcretePerSuperAttributeCollectionConstraint;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 */
class ProductConcreteSuperAttributeForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FIELD_NAME = 'name';

    /**
     * @var string
     */
    protected const FIELD_SKU = 'sku';

    /**
     * @var string
     */
    protected const FIELD_SUPER_ATTRIBUTES = 'superAttributes';

    /**
     * @var string
     */
    protected const VALIDATION_MESSAGE_NOT_BLANK = 'The value cannot be empty. Please fill in this input.';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' => [
                new UniqueProductConcretePerSuperAttributeCollectionConstraint(),
            ],
        ]);
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addNameField($builder)
            ->addSkuField($builder)
            ->addSuperAttributesField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NAME, HiddenType::class, [
            'label' => false,
            'required' => true,
            'constraints' => [
                new NotBlank(['message' => static::VALIDATION_MESSAGE_NOT_BLANK]),
            ],
            'empty_data' => '',
        ]);

        $builder->get(static::FIELD_NAME)->addViewTransformer($this->getFactory()->createEmptyStringTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSkuField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SKU, HiddenType::class, [
            'label' => false,
            'required' => true,
            'constraints' => [
                new NotBlank(['message' => static::VALIDATION_MESSAGE_NOT_BLANK]),
                new SkuRegexConstraint(),
            ],
            'empty_data' => '',
        ]);

        $builder->get(static::FIELD_SKU)->addViewTransformer($this->getFactory()->createEmptyStringTransformer());

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addSuperAttributesField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_SUPER_ATTRIBUTES, CollectionType::class, [
            'entry_type' => SuperAttributeForm::class,
            'allow_add' => true,
            'allow_delete' => true,
            'label' => false,
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }
}
