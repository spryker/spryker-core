<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipGui\Communication\Form;

use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\PriceProductMerchantRelationshipGui\PriceProductMerchantRelationshipGuiConfig;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\PriceProductMerchantRelationshipGui\PriceProductMerchantRelationshipGuiConfig getConfig()
 * @method \Spryker\Zed\PriceProductMerchantRelationshipGui\Communication\PriceProductMerchantRelationshipGuiCommunicationFactory getFactory()
 */
class MerchantRelationshipPriceDimensionForm extends AbstractType
{
    public const OPTION_VALUES_MERCHANT_RELATIONSHIP_CHOICES = 'merchant_relationship_choices';

    protected const FIELD_PLACEHOLDER_MERCHANT_RELATIONSHIP = 'Default prices';
    protected const FIELD_LABEL_MERCHANT_RELATIONSHIP = 'Merchant Price Dimension';

    protected const TEMPLATE_PATH = '@PriceProductMerchantRelationshipGui/ProductManagement/price_dimension.twig';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addMerchantRelationshipCollectionField($builder, $options);
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(static::OPTION_VALUES_MERCHANT_RELATIONSHIP_CHOICES);
        $resolver->setDefaults([
            'label' => false,
            'mapped' => false,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addMerchantRelationshipCollectionField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(PriceProductDimensionTransfer::ID_MERCHANT_RELATIONSHIP, ChoiceType::class, [
            'choices' => $options[static::OPTION_VALUES_MERCHANT_RELATIONSHIP_CHOICES],
            'placeholder' => static::FIELD_PLACEHOLDER_MERCHANT_RELATIONSHIP,
            'label' => static::FIELD_LABEL_MERCHANT_RELATIONSHIP,
            'attr' => [
                'template_path' => $this->getTemplatePath(),
                'data-type' => PriceProductMerchantRelationshipGuiConfig::PRICE_DIMENSION_MERCHANT_RELATIONSHIP,
            ],
        ]);

        return $this;
    }

    /**
     * @return string
     */
    protected function getTemplatePath(): string
    {
        return static::TEMPLATE_PATH;
    }
}
