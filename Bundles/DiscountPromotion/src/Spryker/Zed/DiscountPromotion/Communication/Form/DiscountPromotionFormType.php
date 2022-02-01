<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Communication\Form;

use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Spryker\Shared\DiscountPromotion\DiscountPromotionConfig;
use Spryker\Zed\Discount\Communication\Form\AbstractDiscountExtensionSubFormType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \Spryker\Zed\DiscountPromotion\Communication\DiscountPromotionCommunicationFactory getFactory()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepositoryInterface getRepository()
 */
class DiscountPromotionFormType extends AbstractDiscountExtensionSubFormType
{
    /**
     * @var string
     */
    protected const LABEL_ABSTRACT_SKUS = 'Abstract Product SKU(S)';

    /**
     * @var string
     */
    protected const HELP_ABSTRACT_SKUS = 'Use a comma-separated list for multiple abstract SKUs';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!$this->getRepository()->isAbstractSkusFieldExists()) {
            $this->buildSingleDiscountPromotionForm($builder);

            return;
        }

        $this->buildMultipleDiscountPromotionForm($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function buildSingleDiscountPromotionForm(FormBuilderInterface $builder): void
    {
        $this->addAbstractSkuField($builder)
            ->addAbstractQuantityField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function buildMultipleDiscountPromotionForm(FormBuilderInterface $builder): void
    {
        $this
            ->addAbstractSkusField($builder)
            ->addAbstractQuantityField($builder);

        $builder->get(DiscountPromotionTransfer::ABSTRACT_SKUS)
            ->addModelTransformer($this->getFactory()->createAbstractSkusTransformer());
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAbstractSkuField(FormBuilderInterface $builder)
    {
        $builder->add(
            DiscountPromotionTransfer::ABSTRACT_SKU,
            TextType::class,
            [
                'label' => 'Abstract sku:',
                'constraints' => [
                    new NotBlank(['groups' => DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY]),
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
    protected function addAbstractQuantityField(FormBuilderInterface $builder)
    {
        $builder->add(
            DiscountPromotionTransfer::QUANTITY,
            TextType::class,
            [
                'label' => 'Maximum Quantity:',
                'constraints' => [
                    new NotBlank(['groups' => DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY]),
                    new Regex([
                        'pattern' => '/[0-9]+/',
                    ]),
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
    protected function addAbstractSkusField(FormBuilderInterface $builder)
    {
        $builder->add(
            DiscountPromotionTransfer::ABSTRACT_SKUS,
            TextareaType::class,
            [
                'label' => static::LABEL_ABSTRACT_SKUS,
                'constraints' => [
                    new NotBlank(['groups' => DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY]),
                    $this->getFactory()->createAbstractSkusExistConstraint(),
                ],
                'help' => static::HELP_ABSTRACT_SKUS,
            ],
        );

        return $this;
    }

    /**
     * @return string
     */
    protected function getTemplatePath()
    {
        return 'DiscountPromotion/Form/discount_promotion';
    }
}
