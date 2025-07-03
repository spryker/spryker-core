<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Business\Filter;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\ProductApproval\ProductApprovalConfig;
use Spryker\Zed\ProductApproval\Business\Reader\ProductReaderInterface;
use Spryker\Zed\ProductApproval\Dependency\Facade\ProductApprovalToMessengerFacadeInterface;

class QuoteItemsFilter implements QuoteItemsFilterInterface
{
    /**
     * @var string
     */
    protected const MESSAGE_PARAM_SKU = '%sku%';

    /**
     * @var string
     */
    protected const MESSAGE_INFO_CONCRETE_INACTIVE_PRODUCT_REMOVED = 'product-cart.info.concrete-product-inactive.removed';

    /**
     * @var \Spryker\Zed\ProductApproval\Business\Reader\ProductReaderInterface
     */
    protected $productReader;

    /**
     * @var \Spryker\Zed\ProductApproval\Dependency\Facade\ProductApprovalToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\ProductApproval\Business\Reader\ProductReaderInterface $productReader
     * @param \Spryker\Zed\ProductApproval\Dependency\Facade\ProductApprovalToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(
        ProductReaderInterface $productReader,
        ProductApprovalToMessengerFacadeInterface $messengerFacade
    ) {
        $this->productReader = $productReader;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param list<string> $skusToSkip
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterCartItems(QuoteTransfer $quoteTransfer, array $skusToSkip = []): QuoteTransfer
    {
        $productAbstractSkus = $this->getProductAbstractSkus($quoteTransfer);

        if (!$productAbstractSkus) {
            return $quoteTransfer;
        }

        $productAbstractTransfersIndexedByIdProductAbstract = $this->productReader
            ->getProductAbstractTransfersIndexedByIdProductAbstract($productAbstractSkus);

        foreach ($quoteTransfer->getItems() as $key => $itemTransfer) {
            if (in_array($itemTransfer->getSku(), $skusToSkip)) {
                continue;
            }

            $productAbstractTransfer = $productAbstractTransfersIndexedByIdProductAbstract[$itemTransfer->getIdProductAbstract()] ?? null;
            if ($productAbstractTransfer && $productAbstractTransfer->getApprovalStatus() !== ProductApprovalConfig::STATUS_APPROVED) {
                $quoteTransfer->getItems()->offsetUnset($key);
                $this->addFilterMessage($itemTransfer->getSkuOrFail());
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    protected function addFilterMessage(string $sku): void
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue(static::MESSAGE_INFO_CONCRETE_INACTIVE_PRODUCT_REMOVED);
        $messageTransfer->setParameters([
            static::MESSAGE_PARAM_SKU => $sku,
        ]);

        $this->messengerFacade->addInfoMessage($messageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<int, string>
     */
    protected function getProductAbstractSkus(QuoteTransfer $quoteTransfer): array
    {
        $productAbstractSkus = [];

        if (!$quoteTransfer->getItems()->count()) {
            return $productAbstractSkus;
        }

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $productAbstractSkus[] = $itemTransfer->getAbstractSkuOrFail();
        }

        return array_unique($productAbstractSkus);
    }
}
