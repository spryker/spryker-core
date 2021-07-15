<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\EmptyJsonAttributesConstraint;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\ProductAbstractIdOwnedByMerchantConstraint;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Constraint\UniqueSkuInProductConcreteCollectionConstraint;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 */
class AddProductConcreteForm extends AbstractType
{
    protected const FIELD_ATTRIBUTES = 'attributes';
    protected const FIELD_EXISTING_ATTRIBUTES = 'existing_attributes';
    protected const FIELD_PRODUCTS = 'products';
    protected const FIELD_ID_PRODUCT_ABSTRACT = 'idProductAbstract';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'constraints' => [
                new UniqueSkuInProductConcreteCollectionConstraint(),
            ],
        ]);
    }

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
        $this->addAttributesField($builder)
            ->addExistingAttributesField($builder)
            ->addProductsField($builder)
            ->addIdProductAbstractField($builder);

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
            $formData = $event->getData();

            $formData[static::FIELD_PRODUCTS] = $this->getFactory()->getUtilEncodingService()->decodeJson(
                $formData[static::FIELD_PRODUCTS],
                true
            );

            $event->setData($formData);
        });
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAttributesField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ATTRIBUTES, HiddenType::class, [
            'constraints' => [
                new EmptyJsonAttributesConstraint(),
            ],
        ]);

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addExistingAttributesField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_EXISTING_ATTRIBUTES, HiddenType::class);

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdProductAbstractField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_PRODUCT_ABSTRACT, HiddenType::class, [
            'label' => false,
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new ProductAbstractIdOwnedByMerchantConstraint(),
            ],
        ]);

        return $this;
    }

    /**
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductsField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PRODUCTS, CollectionType::class, [
            'entry_type' => ProductConcreteSuperAttributeForm::class,
            'allow_add' => true,
            'allow_delete' => true,
            'label' => false,
        ]);

        return $this;
    }
}
