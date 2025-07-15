<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Service\Form;

use ArrayObject;
use Generated\Shared\Transfer\ProductClassCriteriaTransfer;
use Generated\Shared\Transfer\ProductClassTransfer;
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
class ProductClassForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_PRODUCT_CLASSES = 'product_classes';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addProductClassField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addProductClassField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PRODUCT_CLASSES, Select2ComboBoxType::class, [
            'label' => 'Product Classes',
            'placeholder' => 'Select product classes',
            'required' => false,
            'multiple' => true,
            'choices' => $this->getProductClassChoices(),
            'attr' => [
                'data-qa' => 'product-classes',
            ],
        ]);

        $builder->get(static::FIELD_PRODUCT_CLASSES)
            ->addModelTransformer($this->createProductClassModelTransformer());

        return $this;
    }

    /**
     * @return array<string, int>
     */
    protected function getProductClassChoices(): array
    {
        $productClassCriteriaTransfer = new ProductClassCriteriaTransfer();
        $productClassCollection = $this->getFactory()
            ->getRepository()
            ->getProductClassCollection($productClassCriteriaTransfer);

        $choices = [];
        foreach ($productClassCollection->getProductClasses() as $productClassTransfer) {
            $choices[$productClassTransfer->getNameOrFail()] = $productClassTransfer->getIdProductClassOrFail();
        }

        return $choices;
    }

    /**
     * @return \Symfony\Component\Form\CallbackTransformer
     */
    protected function createProductClassModelTransformer(): CallbackTransformer
    {
        return new CallbackTransformer(
            function ($productClasses) {
                if (!$productClasses instanceof ArrayObject) {
                    return [];
                }

                $productClassIds = [];
                foreach ($productClasses as $productClass) {
                    if ($productClass instanceof ProductClassTransfer) {
                        $productClassIds[] = $productClass->getIdProductClass();
                    }
                }

                return $productClassIds;
            },
            function ($productClassIds) {
                if (!is_array($productClassIds)) {
                    return new ArrayObject();
                }

                $productClasses = new ArrayObject();
                foreach ($productClassIds as $idProductClass) {
                    $productClasses->append(
                        (new ProductClassTransfer())
                            ->setIdProductClass($idProductClass),
                    );
                }

                return $productClasses;
            },
        );
    }
}
