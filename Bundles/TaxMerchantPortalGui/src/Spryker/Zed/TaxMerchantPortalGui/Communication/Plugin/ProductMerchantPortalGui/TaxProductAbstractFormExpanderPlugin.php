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
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\TaxMerchantPortalGui\Communication\TaxMerchantPortalGuiCommunicationFactory getFactory()
 */
class TaxProductAbstractFormExpanderPlugin extends AbstractPlugin implements ProductAbstractFormExpanderPluginInterface
{
    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\ProductAbstractForm::GROUP_WITH_STORES
     *
     * @var string
     */
    protected const GROUP_WITH_STORES = 'stores';

    /**
     * @var string
     */
    protected const LABEL_ID_TAX_SET = 'Tax Set';

    /**
     * @var string
     */
    protected const PLACEHOLDER_ID_TAX_SET = 'Select tax set';

    /**
     * @var string
     */
    protected const MESSAGE_VALIDATION_NOT_BLANK_ERROR = 'The value cannot be blank. Please fill in this input';

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
            'empty_data' => '',
            'constraints' => [
                $this->createNotBlankConstraint(),
            ],
        ]);

        return $builder;
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\NotBlank
     */
    protected function createNotBlankConstraint(): NotBlank
    {
        return new NotBlank([
            'message' => static::MESSAGE_VALIDATION_NOT_BLANK_ERROR,
            'groups' => static::GROUP_WITH_STORES,
        ]);
    }
}
