<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder\Builder;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ItemValidationTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Generated\Shared\Transfer\QuickOrderTransfer;
use Spryker\Client\QuickOrder\Expander\ProductConcreteExpanderInterface;
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
     * @var \Spryker\Client\QuickOrder\Expander\ProductConcreteExpanderInterface
     */
    protected $productConcreteExpander;

    /**
     * @param \Spryker\Client\QuickOrder\Product\ProductConcreteResolverInterface $productConcreteResolver
     * @param \Spryker\Client\QuickOrder\Validator\QuickOrderItemValidatorInterface $quickOrderItemValidator
     * @param \Spryker\Client\QuickOrder\Expander\ProductConcreteExpanderInterface $productConcreteExpander
     */
    public function __construct(
        ProductConcreteResolverInterface $productConcreteResolver,
        QuickOrderItemValidatorInterface $quickOrderItemValidator,
        ProductConcreteExpanderInterface $productConcreteExpander
    ) {
        $this->productConcreteResolver = $productConcreteResolver;
        $this->quickOrderItemValidator = $quickOrderItemValidator;
        $this->productConcreteExpander = $productConcreteExpander;
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
                $quickOrderItemTransfers->append($quickOrderItemTransfer);
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
        $productConcreteTransfer = $this->productConcreteResolver->findProductConcreteWithProductAbstractBySku($quickOrderItemTransfer->getSku());

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
        $itemValidationTransfer = (new ItemValidationTransfer())->setItem(
            $this->getItemTransfer($quickOrderItemTransfer)
        );
        $itemValidationTransfer = $this->quickOrderItemValidator->validate($itemValidationTransfer);

        if ($itemValidationTransfer->getMessages()->count() > 0) {
            $quickOrderItemTransfer->setMessages($itemValidationTransfer->getMessages());
        }

        if (!$itemValidationTransfer->getSuggestedValues()) {
            return $quickOrderItemTransfer;
        }

        $quickOrderItemTransfer = $this->updateQuickOrderItemTransfer($quickOrderItemTransfer, $itemValidationTransfer);

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

        $expandedProductConcretes = $this->productConcreteExpander->expand([$quickOrderItemTransfer->getProductConcrete()]);
        $quickOrderItemTransfer->setProductConcrete($expandedProductConcretes[0]);

        return $quickOrderItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function getItemTransfer(QuickOrderItemTransfer $quickOrderItemTransfer): ItemTransfer
    {
        $productConcreteTransfer = $quickOrderItemTransfer->getProductConcrete();

        return (new ItemTransfer())
            ->setSku($quickOrderItemTransfer->getSku())
            ->setId($productConcreteTransfer ? $productConcreteTransfer->getIdProductConcrete() : null)
            ->setIdProductAbstract($productConcreteTransfer ? $productConcreteTransfer->getFkProductAbstract() : null)
            ->setQuantity($quickOrderItemTransfer->getQuantity());
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     * @param \Generated\Shared\Transfer\ItemValidationTransfer $itemValidationTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer
     */
    protected function updateQuickOrderItemTransfer(QuickOrderItemTransfer $quickOrderItemTransfer, ItemValidationTransfer $itemValidationTransfer): QuickOrderItemTransfer
    {
        $suggestedValues = $itemValidationTransfer->getSuggestedValues()->modifiedToArray();

        return $quickOrderItemTransfer->fromArray($suggestedValues, true);
    }
}
