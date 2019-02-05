<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder\Builder;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;
use Spryker\Client\QuickOrder\Product\ProductConcreteResolverInterface;
use Spryker\Client\QuickOrder\Validator\QuickOrderItemValidatorInterface;

class QuickOrderTransferBuilder implements QuickOrderTransferBuilderInterface
{
    protected const MESSAGE_TYPE_ERROR = 'error';
    protected const ERROR_MESSAGE_INVALID_SKU = 'quick-order.upload-order.errors.upload-order-invalid-sku-item';

    /**
     * @var \Spryker\Client\QuickOrder\Product\ProductConcreteResolverInterface
     */
    protected $productConcreteResolver;

    /**
     * @var \Spryker\Client\QuickOrder\Validator\QuickOrderItemValidatorInterface
     */
    protected $quickOrderItemValidator;

    /**
     * @var \Spryker\Client\QuickOrderExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface[]
     */
    protected $productConcreteExpanderPlugins;

    /**
     * @param \Spryker\Client\QuickOrder\Product\ProductConcreteResolverInterface $productConcreteResolver
     * @param \Spryker\Client\QuickOrder\Validator\QuickOrderItemValidatorInterface $quickOrderItemValidator
     * @param \Spryker\Client\QuickOrderExtension\Dependency\Plugin\ProductConcreteExpanderPluginInterface[] $productConcreteExpanderPlugins
     */
    public function __construct(
        ProductConcreteResolverInterface $productConcreteResolver,
        QuickOrderItemValidatorInterface $quickOrderItemValidator,
        array $productConcreteExpanderPlugins
    ) {
        $this->productConcreteResolver = $productConcreteResolver;
        $this->quickOrderItemValidator = $quickOrderItemValidator;
        $this->productConcreteExpanderPlugins = $productConcreteExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function build(QuickOrderTransfer $quickOrderTransfer): QuickOrderTransfer
    {
        $quickOrderItemTransfers = new ArrayObject();

        foreach ($quickOrderTransfer->getItems() as $quickOrderItemTransfer) {
            if (!$quickOrderItemTransfer->getSku()) {
                continue;
            }

            $quickOrderItemTransfer = $this->resolveProductConcrete($quickOrderItemTransfer);
            $quickOrderItemTransfer = $this->validateQuickOrderItem($quickOrderItemTransfer);
            $quickOrderItemTransfer = $this->expandProductConcrete($quickOrderItemTransfer);

            $quickOrderItemTransfers->append($quickOrderItemTransfer);
        }

        $quickOrderTransfer->setItems($quickOrderItemTransfers);

        return $quickOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer
     */
    protected function resolveProductConcrete(QuickOrderItemTransfer $quickOrderItemTransfer): QuickOrderItemTransfer
    {
        $productConcreteTransfer = $this->productConcreteResolver->findProductConcreteBySku($quickOrderItemTransfer->getSku());

        if ($productConcreteTransfer === null) {
            $productConcreteTransfer = (new ProductConcreteTransfer())->setSku($quickOrderItemTransfer->getSku());
            $quickOrderItemTransfer->addMessage((new MessageTransfer())
                ->setType(static::MESSAGE_TYPE_ERROR)
                ->setValue(static::ERROR_MESSAGE_INVALID_SKU));
        }

        $quickOrderItemTransfer->setProductConcrete($productConcreteTransfer);

        return $quickOrderItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer
     */
    protected function validateQuickOrderItem(QuickOrderItemTransfer $quickOrderItemTransfer): QuickOrderItemTransfer
    {
        $itemTransfer = $this->getItemTransfer($quickOrderItemTransfer);
        $itemValidationResponseTransfer = $this->quickOrderItemValidator->validate($itemTransfer);

        if ($itemValidationResponseTransfer->getMessages()->count()) {
            $quickOrderItemTransfer->setMessages($itemValidationResponseTransfer->getMessages());
        }

        if (!$itemValidationResponseTransfer->getRecommendedValues()) {
            return $quickOrderItemTransfer;
        }

        $quickOrderItemTransfer->fromArray($itemValidationResponseTransfer->getRecommendedValues()->toArray(), true);

        return $quickOrderItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer
     */
    protected function expandProductConcrete(QuickOrderItemTransfer $quickOrderItemTransfer): QuickOrderItemTransfer
    {
        $quickOrderItemTransfer->requireProductConcrete();

        if (!$quickOrderItemTransfer->getProductConcrete()->getIdProductConcrete()) {
            return $quickOrderItemTransfer;
        }

        foreach ($this->productConcreteExpanderPlugins as $productConcreteExpanderPlugin) {
            $expandedProductConcrete = $productConcreteExpanderPlugin->expand([$quickOrderItemTransfer->getProductConcrete()]);
        }

        if (isset($expandedProductConcrete)) {
            $quickOrderItemTransfer->setProductConcrete($expandedProductConcrete[0]);
        }

        return $quickOrderItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function getItemTransfer(QuickOrderItemTransfer $quickOrderItemTransfer): ItemTransfer
    {
        $itemTransfer = new ItemTransfer();
        $itemTransfer->fromArray($quickOrderItemTransfer->toArray());

        return $itemTransfer;
    }
}
