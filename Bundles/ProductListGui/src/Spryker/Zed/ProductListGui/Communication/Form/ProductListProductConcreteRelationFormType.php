<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Form;

use Generated\Shared\Transfer\ProductListAggregateFormTransfer;
use Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\ProductListGui\ProductListGuiConfig getConfig()
 * @method \Spryker\Zed\ProductListGui\Communication\ProductListGuiCommunicationFactory getFactory()
 */
class ProductListProductConcreteRelationFormType extends AbstractType
{
    public const FIELD_ID_PRODUCT_LIST = ProductListProductConcreteRelationTransfer::ID_PRODUCT_LIST;
    public const FIELD_ASSIGNED_PRODUCT_IDS = ProductListAggregateFormTransfer::ASSIGNED_PRODUCT_IDS;
    public const FIELD_PRODUCT_IDS_TO_BE_ASSIGNED = ProductListAggregateFormTransfer::PRODUCT_IDS_TO_BE_ASSIGNED;
    public const FIELD_PRODUCT_IDS_TO_BE_DEASSIGNED = ProductListAggregateFormTransfer::PRODUCT_IDS_TO_BE_DE_ASSIGNED;
    public const FIELD_FILE_UPLOAD = 'products_upload';

    public const PRODUCT_IDS = ProductListProductConcreteRelationTransfer::PRODUCT_IDS;
    public const BLOCK_PREFIX = 'productListProductConcreteRelationTransfer';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => ProductListProductConcreteRelationTransfer::class,
            'label' => false,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::BLOCK_PREFIX;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addIdProductListField($builder)
            ->addUploadFileField($builder)
            ->addProductIdsField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdProductListField(FormBuilderInterface $builder)
    {
        $builder->add(
            static::FIELD_ID_PRODUCT_LIST,
            HiddenType::class
        );

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductIdsField(FormBuilderInterface $builder)
    {
        $builder->add(static::PRODUCT_IDS, HiddenType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addUploadFileField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FILE_UPLOAD, FileType::class, [
            'label' => 'Import Product List',
            'required' => false,
            'mapped' => false,
        ]);

        return $this;
    }
}
