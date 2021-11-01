<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Form\Type;

use ArrayObject;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\ProductMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 */
class ProductImageSetFormType extends AbstractType
{
    /**
     * @var string
     */
    protected const MESSAGE_VALIDATION_NOT_BLANK_ERROR = 'The value cannot be blank. Please fill in this input';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductImageSetTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options = [])
    {
        $this->addIdProductImageSet($builder)
            ->addNameField($builder)
            ->addProductImageCollectionForm($builder)
            ->addLocaleHiddenField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdProductImageSet(FormBuilderInterface $builder)
    {
        $builder->add(ProductImageSetTransfer::ID_PRODUCT_IMAGE_SET, HiddenType::class, [
            'empty_data' => null,
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
        $builder->add(ProductImageSetTransfer::NAME, TextType::class, [
            'required' => true,
            'empty_data' => '',
            'constraints' => [
                new NotBlank(['message' => static::MESSAGE_VALIDATION_NOT_BLANK_ERROR]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addLocaleHiddenField(FormBuilderInterface $builder)
    {
        $builder->add(ProductImageSetTransfer::LOCALE, HiddenType::class);
        $builder->get(ProductImageSetTransfer::LOCALE)->addModelTransformer(
            $this->getFactory()->createLocaleTransformer(),
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductImageCollectionForm(FormBuilderInterface $builder)
    {
        $builder->add(ProductImageSetTransfer::PRODUCT_IMAGES, CollectionType::class, [
            'entry_type' => ProductImageFormType::class,
            'empty_data' => new ArrayObject([]),
            'label' => false,
            'allow_add' => true,
            'allow_delete' => true,
            'allow_extra_fields' => true,
        ]);

        return $this;
    }
}
