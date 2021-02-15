<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxMerchantPortalGui\Communication\Plugin\ProductMerchantPortalGui;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\ProductAbstractFormExpanderPluginInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\TaxMerchantPortalGui\Communication\TaxMerchantPortalGuiCommunicationFactory getFactory()
 */
class TaxProductAbstractFormExpanderPlugin extends AbstractPlugin implements ProductAbstractFormExpanderPluginInterface
{
    protected const LABEL_ID_TAX_SET = 'Tax Set';
    protected const PLACEHOLDER_ID_TAX_SET = 'Select tax set';

    /**
     * {@inheritDoc}
     * - Expands ProductAbstractForm with Tax Set field.
     *
     * @api
     *
     * @phpstan-param \Symfony\Component\Form\FormBuilderInterface<mixed> $builder
     * @phpstan-param array<mixed> $options
     *
     * @phpstan-return \Symfony\Component\Form\FormBuilderInterface<mixed>
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $builder->add(ProductAbstractTransfer::ID_TAX_SET, ChoiceType::class, [
            'label' => static::LABEL_ID_TAX_SET,
            'placeholder' => static::PLACEHOLDER_ID_TAX_SET,
            'required' => false,
            'choices' => $this->getFactory()->createTaxProductAbstractFormDataProvider()->getTaxChoices(),
            'empty_data' => (string)$builder->getData()->getIdTaxSet(),
        ]);

        return $builder;
    }
}
