<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder\Builder;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;
use Spryker\Client\QuickOrder\Product\ProductConcreteResolverInterface;
use Spryker\Client\QuickOrder\Validator\ProductConcreteValidatorInterface;

class QuickOrderTransferBuilder implements QuickOrderTransferBuilderInterface
{
    protected const ERROR_MESSAGE_INVALID_SKU = 'quick-order.upload-order.errors.upload-order-invalid-sku-item';

    /**
     * @var \Spryker\Client\QuickOrder\Product\ProductConcreteResolverInterface
     */
    protected $productConcreteResolver;

    /**
     * @var \Spryker\Client\QuickOrder\Validator\ProductConcreteValidatorInterface
     */
    protected $productConcreteValidator;

    /**
     * @var \Spryker\Client\QuickOrderExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface[]
     */
    protected $productConcreteExpanderPlugins;

    /**
     * @param \Spryker\Client\QuickOrder\Product\ProductConcreteResolverInterface $productConcreteResolver
     * @param \Spryker\Client\QuickOrder\Validator\ProductConcreteValidatorInterface $productConcreteValidator
     * @param \Spryker\Client\QuickOrderExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface[] $productConcreteExpanderPlugins
     */
    public function __construct(
        ProductConcreteResolverInterface $productConcreteResolver,
        ProductConcreteValidatorInterface $productConcreteValidator,
        array $productConcreteExpanderPlugins
    ) {
        $this->productConcreteResolver = $productConcreteResolver;
        $this->productConcreteValidator = $productConcreteValidator;
        $this->productConcreteExpanderPlugins = $productConcreteExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function build(QuickOrderTransfer $quickOrderTransfer): QuickOrderTransfer
    {
        foreach ($quickOrderTransfer->getItems() as $quickOrderItemTransfer) {
            if (empty($quickOrderItemTransfer->getSku())) {
                continue;
            }

            $this->resolveProductConcrete($quickOrderItemTransfer);
            $this->validateQuickOrderItem($quickOrderItemTransfer);
            $this->expandProductConcrete($quickOrderItemTransfer);
        }

        return $quickOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return void
     */
    protected function resolveProductConcrete(QuickOrderItemTransfer $quickOrderItemTransfer): void
    {
        $productConcreteTransfer = $this->productConcreteResolver->findProductConcreteBySku($quickOrderItemTransfer->getSku());

        if ($productConcreteTransfer === null) {
            $productConcreteTransfer = (new ProductConcreteTransfer())->setSku($quickOrderItemTransfer->getSku());
            $quickOrderItemTransfer->addErrorMessage((new MessageTransfer())->setValue(static::ERROR_MESSAGE_INVALID_SKU));
        }

        $quickOrderItemTransfer->setProductConcrete($productConcreteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return void
     */
    protected function validateQuickOrderItem(QuickOrderItemTransfer $quickOrderItemTransfer): void
    {
        $quickOrderValidationResponseTransfer = $this->productConcreteValidator->validate($quickOrderItemTransfer);
        $quickOrderItemTransfer->fromArray($quickOrderValidationResponseTransfer->modifiedToArray(), true);

        if (count($quickOrderValidationResponseTransfer->getCorrectValues())) {
            foreach ($quickOrderValidationResponseTransfer->getCorrectValues() as $correctValue) {
                $quickOrderItemTransfer->fromArray($correctValue, true);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return void
     */
    protected function expandProductConcrete(QuickOrderItemTransfer $quickOrderItemTransfer): void
    {
        if ($quickOrderItemTransfer->getProductConcrete()->getIdProductConcrete()) {
            foreach ($this->productConcreteExpanderPlugins as $productConcreteExpanderPlugin) {
                $expandedProductConcrete = $productConcreteExpanderPlugin->expand([$quickOrderItemTransfer->getProductConcrete()]);
            }

            if (isset($expandedProductConcrete)) {
                $quickOrderItemTransfer->setProductConcrete($expandedProductConcrete[0]);
            }
        }
    }
}
