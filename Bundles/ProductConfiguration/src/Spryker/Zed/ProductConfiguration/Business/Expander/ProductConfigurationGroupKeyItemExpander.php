<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Business\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Spryker\Zed\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingServiceInterface;
use Spryker\Zed\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilTextServiceInterface;

class ProductConfigurationGroupKeyItemExpander implements ProductConfigurationGroupKeyItemExpanderInterface
{
    /**
     * @uses \Spryker\Service\UtilText\Model\Hash::MD5
     */
    protected const MD5 = 'md5';

    /**
     * @var \Spryker\Zed\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilTextServiceInterface
     */
    protected $utilTextService;

    /**
     * @param \Spryker\Zed\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingServiceInterface $utilEncodingService
     * @param \Spryker\Zed\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilTextServiceInterface $utilTextService
     */
    public function __construct(
        ProductConfigurationToUtilEncodingServiceInterface $utilEncodingService,
        ProductConfigurationToUtilTextServiceInterface $utilTextService
    ) {
        $this->utilEncodingService = $utilEncodingService;
        $this->utilTextService = $utilTextService;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandProductConfigurationItemsWithGroupKey(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$this->isProductConfigurationItem($itemTransfer)) {
                continue;
            }

            $itemTransfer->setGroupKey(
                $this->buildProductConfigurationGroupKey($itemTransfer)
            );
        }

        return $cartChangeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isProductConfigurationItem(ItemTransfer $itemTransfer): bool
    {
        return (bool)$itemTransfer->getProductConfigurationInstance();
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    protected function buildProductConfigurationGroupKey(ItemTransfer $itemTransfer): string
    {
        $itemTransfer
            ->requireGroupKey();

        $productConfigurationInstanceHashKey = $this->getProductConfigurationHashKey(
            $itemTransfer->getProductConfigurationInstance()
        );

        return sprintf(
            '%s-%s',
            $itemTransfer->getGroupKey(),
            $productConfigurationInstanceHashKey
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return string
     */
    protected function getProductConfigurationHashKey(
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): string {
        $encodedProductConfigurationInstanceData = $this->utilEncodingService->encodeJson(
            $productConfigurationInstanceTransfer->toArray()
        );

        return $this->utilTextService->hashValue($encodedProductConfigurationInstanceData, static::MD5);
    }
}
