<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractTypeTransfer;
use Spryker\Zed\Gui\Communication\Form\Type\Select2ComboBoxType;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig getConfig()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 */
class ProductAbstractTypeForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_PRODUCT_ABSTRACT_TYPES = 'product_abstract_types';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addProductAbstractTypeField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductAbstractTypeField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PRODUCT_ABSTRACT_TYPES, Select2ComboBoxType::class, [
            'label' => 'Product Types',
            'placeholder' => 'Select product types',
            'required' => false,
            'multiple' => true,
            'choices' => $this->getProductAbstractTypeChoices(),
            'attr' => [
                'data-qa' => 'product-abstract-types',
            ],
        ]);

        $builder->get(static::FIELD_PRODUCT_ABSTRACT_TYPES)
            ->addModelTransformer($this->createProductAbstractTypeModelTransformer());

        return $this;
    }

    /**
     * @return array<string, int>
     */
    protected function getProductAbstractTypeChoices(): array
    {
        $productAbstractTypeCollection = $this->getFacade()
            ->getProductAbstractTypeCollection();

        $choices = [];
        foreach ($productAbstractTypeCollection->getProductAbstractTypes() as $productAbstractTypeTransfer) {
            $choices[$productAbstractTypeTransfer->getNameOrFail()] = $productAbstractTypeTransfer->getIdProductAbstractTypeOrFail();
        }

        return $choices;
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createProductAbstractTypeModelTransformer(): CallbackTransformer
    {
        return new CallbackTransformer(
            function ($productAbstractTypes) {
                if (!$productAbstractTypes instanceof ArrayObject) {
                    return [];
                }

                $productAbstractTypeIds = [];
                foreach ($productAbstractTypes as $productAbstractType) {
                    if ($productAbstractType instanceof ProductAbstractTypeTransfer) {
                        $productAbstractTypeIds[] = $productAbstractType->getIdProductAbstractType();
                    }
                }

                return $productAbstractTypeIds;
            },
            function ($productAbstractTypeIds) {
                if (!is_array($productAbstractTypeIds)) {
                    return new ArrayObject();
                }

                $productAbstractTypes = new ArrayObject();
                foreach ($productAbstractTypeIds as $idProductAbstractType) {
                    $productAbstractTypes->append(
                        (new ProductAbstractTypeTransfer())
                            ->setIdProductAbstractType($idProductAbstractType),
                    );
                }

                return $productAbstractTypes;
            },
        );
    }
}
