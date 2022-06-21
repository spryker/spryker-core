<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cart\Business\Expander;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\Cart\Dependency\Service\CartToUtilTextServiceInterface;

class GroupKeyExpander implements GroupKeyExpanderInterface
{
    /**
     * @uses \Spryker\Service\UtilText\Model\Hash::MD5
     *
     * @var string
     */
    protected const MD5 = 'md5';

    /**
     * @var \Spryker\Zed\Cart\Dependency\Service\CartToUtilTextServiceInterface
     */
    protected CartToUtilTextServiceInterface $utilTextService;

    /**
     * @param \Spryker\Zed\Cart\Dependency\Service\CartToUtilTextServiceInterface $utilTextService
     */
    public function __construct(CartToUtilTextServiceInterface $utilTextService)
    {
        $this->utilTextService = $utilTextService;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandItemGroupKeysWithCartIdentifier(CartChangeTransfer $cartChangeTransfer): CartChangeTransfer
    {
        $quoteTransfer = $cartChangeTransfer->getQuote();

        if (!$quoteTransfer || !$quoteTransfer->getIdQuote()) {
            return $cartChangeTransfer;
        }

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setGroupKey(
                $this->getExpandedGroupKey($itemTransfer->getGroupKeyOrFail(), $quoteTransfer->getIdQuote()),
            );
        }

        return $cartChangeTransfer;
    }

    /**
     * @param string $groupKey
     * @param int $idQuote
     *
     * @return string
     */
    protected function getExpandedGroupKey(string $groupKey, int $idQuote): string
    {
        return sprintf('%s_%s', $groupKey, $this->utilTextService->hashValue((string)$idQuote, static::MD5));
    }
}
