<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Communication\Form;

use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListCreateFormExpanderPluginInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductListMerchantRelationForm extends AbstractType
{
    public const FIELD_NAME = ProductListTransfer::FK_MERCHANT_RELATIONSHIP;
    public const OPTION_MERCHANT_RELATION_LIST = 'merchant-relation-names';
    public const OPTION_DISABLE_GENERAL = ProductListCreateFormExpanderPluginInterface::OPTION_DISABLE_GENERAL;

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([
            'data_class' => ProductListTransfer::class,
            static::OPTION_DISABLE_GENERAL,
            static::OPTION_MERCHANT_RELATION_LIST,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addMerchantRelationType(
            $builder,
            $options[static::OPTION_DISABLE_GENERAL],
            $options[static::OPTION_MERCHANT_RELATION_LIST]
        );
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param bool $disabled
     * @param int[] $merchantRelationList ['relation name' => 'id']
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    protected function addMerchantRelationType(
        FormBuilderInterface $builder,
        bool $disabled,
        array $merchantRelationList
    ): FormBuilderInterface {

        $builder->add(static::FIELD_NAME, ChoiceType::class, [
            'label' => 'Merchant relation',
            'required' => false,
            'disabled' => $disabled,
            'choices' => $merchantRelationList,
        ]);

        return $builder;
    }
}
