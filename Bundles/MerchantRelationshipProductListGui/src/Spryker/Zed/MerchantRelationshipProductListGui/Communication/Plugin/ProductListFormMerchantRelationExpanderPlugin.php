<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Communication\Plugin;

use Generated\Shared\Transfer\ProductListTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListCreateFormExpanderPluginInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductListGui\Communication\MerchantRelationshipProductListGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\MerchantRelationshipProductListGui\Business\MerchantRelationshipProductListGuiFacadeInterface getFacade()
 */
class ProductListFormMerchantRelationExpanderPlugin extends AbstractPlugin implements ProductListCreateFormExpanderPluginInterface
{
    public const FIELD_NAME = ProductListTransfer::FK_MERCHANT_RELATIONSHIP;
    public const OPTION_MERCHANT_RELATION_LIST = 'merchant-relation-names';

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::FIELD_NAME;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(static::FIELD_NAME, ChoiceType::class, [
            'label' => 'Merchant relation',
            'required' => false,
            'disabled' => $options[static::OPTION_DISABLE_GENERAL],
            'choices' => $options[static::OPTION_MERCHANT_RELATION_LIST],
        ]);
    }

    /**
     * @param array $options
     *
     * @return array
     */
    public function getOptions(array $options): array
    {
        return $this->getFactory()
            ->createPluginListMerchantRelationDataProvider()
            ->getOptions($options);
    }
}
