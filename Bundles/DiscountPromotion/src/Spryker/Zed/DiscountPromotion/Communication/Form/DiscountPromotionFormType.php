<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DiscountPromotion\Communication\Form;

use Generated\Shared\Transfer\DiscountPromotionTransfer;
use Spryker\Shared\DiscountPromotion\DiscountPromotionConfig;
use Spryker\Zed\Discount\Communication\Form\AbstractDiscountExtensionSubFormType;
use Spryker\Zed\Gui\Communication\Form\Type\FormattedNumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\DiscountPromotion\Communication\DiscountPromotionCommunicationFactory getFactory()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionRepositoryInterface getRepository()
 * @method \Spryker\Zed\DiscountPromotion\Persistence\DiscountPromotionQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\DiscountPromotion\DiscountPromotionConfig getConfig()
 * @method \Spryker\Zed\DiscountPromotion\Business\DiscountPromotionFacadeInterface getFacade()
 */
class DiscountPromotionFormType extends AbstractDiscountExtensionSubFormType
{
    /**
     * @var string
     */
    public const OPTION_LOCALE = 'locale';

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
            $this->buildSingleDiscountPromotionForm($builder, $options);

            return;
        }

        $this->buildMultipleDiscountPromotionForm($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            static::OPTION_LOCALE => null,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    protected function buildSingleDiscountPromotionForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addAbstractSkuField($builder)
            ->addAbstractQuantityField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    protected function buildMultipleDiscountPromotionForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addAbstractSkusField($builder)
            ->addAbstractQuantityField($builder, $options);

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
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addAbstractQuantityField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            DiscountPromotionTransfer::QUANTITY,
            FormattedNumberType::class,
            [
                'label' => 'Maximum Quantity:',
                'constraints' => [
                    new NotBlank(['groups' => DiscountPromotionConfig::DISCOUNT_COLLECTOR_STRATEGY]),
                ],
                'locale' => $options[static::OPTION_LOCALE],
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
