<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductBundle\Business\ProductBundle\Active\PreCheck;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface;

class ProductBundleCartActiveCheck implements ProductBundleCartActiveCheckInterface
{
    public const CART_PRE_CHECK_ACTIVE_FAILED = 'cart.pre.check.active.failed';
    public const SKU_TRANSLATION_PARAMETER = '%sku%';

    /**
     * @var \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface
     */
    protected $productBundleQueryContainer;

    /**
     * @param \Spryker\Zed\ProductBundle\Persistence\ProductBundleQueryContainerInterface $productBundleQueryContainer
     */
    public function __construct(ProductBundleQueryContainerInterface $productBundleQueryContainer)
    {
        $this->productBundleQueryContainer = $productBundleQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkCartAvailability(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $messages = new ArrayObject();

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $this->checkIsBundledProductsActive($itemTransfer, $messages);
        }

        return $this->createCartPreCheckResponseTransfer($messages);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \ArrayObject $messages
     *
     * @return void
     */
    protected function checkIsBundledProductsActive(ItemTransfer $itemTransfer, ArrayObject $messages): void
    {
        $productBundleEntities = $this->findBundledProducts($itemTransfer->getSku());

        foreach ($productBundleEntities as $productBundleEntity) {
            $bundledProductConcreteEntity = $productBundleEntity->getSpyProductRelatedByFkBundledProduct();

            if (!$bundledProductConcreteEntity->getIsActive()) {
                $messages[] = $this->createItemIsNotActiveMessageTransfer($bundledProductConcreteEntity->getSku());
            }
        }
    }

    /**
     * @param string $sku
     *
     * @return \Orm\Zed\ProductBundle\Persistence\SpyProductBundle[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findBundledProducts($sku): ObjectCollection
    {
        return $this->productBundleQueryContainer
            ->queryBundleProductBySku($sku)
            ->find();
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\MessageTransfer[] $messages
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function createCartPreCheckResponseTransfer(ArrayObject $messages)
    {
        return (new CartPreCheckResponseTransfer())
            ->setIsSuccess(count($messages) == 0)
            ->setMessages($messages);
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createItemIsNotActiveMessageTransfer($sku)
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue(static::CART_PRE_CHECK_ACTIVE_FAILED);
        $messageTransfer->setParameters([
            static::SKU_TRANSLATION_PARAMETER => $sku,
        ]);

        return $messageTransfer;
    }
}
