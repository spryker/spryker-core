<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCart;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\PersistentCart\PersistentCartFactory getFactory()
 */
class PersistentCartClient extends AbstractClient implements PersistentCartClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     * TODO:
     * - session strategy: additionally stores quote in session internally after zed request
     * - persistent strategy: make zed request with $itemTransfer as parameter and do everything in zed side (read quote from DB, prepare PersistentCartChangeTransfer, recalculate, store quote in db) then store in session after zed request
     */
    public function addItem(ItemTransfer $itemTransfer)
    {
        return $this->getFactory()->getQuoteStorageStrategy()->addItem($itemTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addItems(array $itemTransfers)
    {
        return $this->getFactory()->getQuoteStorageStrategy()->addItems($itemTransfers);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItem($sku, $groupKey = null)
    {
        return $this->getFactory()->getQuoteStorageStrategy()->removeItem($sku, $groupKey);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeItems(ArrayObject $items)
    {
        return $this->getFactory()->getQuoteStorageStrategy()->removeItems($items);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param string|null $groupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function changeItemQuantity($sku, $groupKey = null, $quantity = 1)
    {
        return $this->getFactory()->getQuoteStorageStrategy()->changeItemQuantity($sku, $groupKey, $quantity);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param string|null $groupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function decreaseItemQuantity($sku, $groupKey = null, $quantity = 1)
    {
        return $this->getFactory()->getQuoteStorageStrategy()->decreaseItemQuantity($sku, $groupKey, $quantity);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sku
     * @param string|null $groupKey
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function increaseItemQuantity($sku, $groupKey = null, $quantity = 1)
    {
        return $this->getFactory()->getQuoteStorageStrategy()->increaseItemQuantity($sku, $groupKey, $quantity);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function reloadItems()
    {
        $this->getFactory()->getQuoteStorageStrategy()->reloadItems();
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated
     *
     * @api
     *
     * @return \Spryker\Client\PersistentCart\Zed\PersistentCartStubInterface
     */
    public function getZedStub()
    {
        return $this->getFactory()->getQuoteStorageStrategy()->getZedStub();
    }

    /**
     * @return \Spryker\Client\PersistentCart\Dependency\Client\CartToQuoteInterface
     */
    protected function getQuoteClient()
    {
        return $this->getFactory()->getQuoteClient();
    }

}
