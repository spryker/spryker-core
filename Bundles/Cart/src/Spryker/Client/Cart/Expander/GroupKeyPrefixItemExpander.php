<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Cart\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Client\Cart\Dependency\Client\CartToUtilTextServiceInterface;
use Spryker\Client\CartExtension\Dependency\Plugin\QuoteItemFinderPluginInterface;

class GroupKeyPrefixItemExpander implements GroupKeyPrefixItemExpanderInterface
{
    /**
     * @var string
     */
    protected const PARAM_SEPARATE_PRODUCT = 'separate_product';

    /**
     * @var string
     */
    protected const PARAM_PREFIX = '';

    /**
     * @var \Spryker\Client\CartExtension\Dependency\Plugin\QuoteItemFinderPluginInterface
     */
    protected $quoteItemFinderPlugin;

    /**
     * @var \Spryker\Client\Cart\Dependency\Client\CartToUtilTextServiceInterface
     */
    private $utilTextService;

    /**
     * @param \Spryker\Client\CartExtension\Dependency\Plugin\QuoteItemFinderPluginInterface $quoteItemFinderPlugin
     * @param \Spryker\Client\Cart\Dependency\Client\CartToUtilTextServiceInterface $utilTextService
     */
    public function __construct(
        QuoteItemFinderPluginInterface $quoteItemFinderPlugin,
        CartToUtilTextServiceInterface $utilTextService
    ) {
        $this->quoteItemFinderPlugin = $quoteItemFinderPlugin;
        $this->utilTextService = $utilTextService;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param array<string, mixed> $params
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandCartItemsWithGroupKeyPrefix(CartChangeTransfer $cartChangeTransfer, array $params = []): CartChangeTransfer
    {
        if (!isset($params[static::PARAM_SEPARATE_PRODUCT])) {
            return $cartChangeTransfer;
        }

        $quoteTransfer = $cartChangeTransfer->getQuote();
        if (!$quoteTransfer) {
            return $cartChangeTransfer;
        }

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!$this->quoteItemFinderPlugin->findItem($quoteTransfer, $itemTransfer->getSkuOrFail(), $itemTransfer->getGroupKey())) {
                continue;
            }

            $itemTransfer->setGroupKeyPrefix($this->buildGroupKeyPrefix());
        }

        return $cartChangeTransfer;
    }

    /**
     * @return string
     */
    protected function buildGroupKeyPrefix(): string
    {
        return $this->utilTextService->generateUniqueId(static::PARAM_PREFIX, true);
    }
}
