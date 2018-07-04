<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipProductListGui\Communication\Plugin\ProuctListGui;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductListGuiExtension\Dependency\Plugin\ProductListOwnerTypeFormExpanderPluginInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\MerchantRelationshipProductListGui\Communication\MerchantRelationshipProductListGuiCommunicationFactory getFactory()
 */
class MerchantRelationshipProductListOwnerTypeFormExpanderPlugin extends AbstractPlugin implements ProductListOwnerTypeFormExpanderPluginInterface
{
    public const OWNER_TYPE = 'Merchant Relationship';

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getOwnerType(): string
    {
        return static::OWNER_TYPE;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $merchantRelationshipChoiceFormDataProvider = $this->getFactory()->createMerchantRelationshipChoiceFormDataProvider();
        $merchantRelationshipChoiceFormType = $this->getFactory()->createMerchantRelationshipChoiceFormType();

        $merchantRelationshipChoiceFormType->buildForm(
            $builder,
            $merchantRelationshipChoiceFormDataProvider->getOptions()
        );
    }
}
