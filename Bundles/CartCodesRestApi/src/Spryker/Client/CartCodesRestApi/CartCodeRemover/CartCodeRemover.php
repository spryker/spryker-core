<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CartCodesRestApi\CartCodeRemover;

use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RemoveCodeRequestTransfer;
use Spryker\Client\CartCodesRestApi\Zed\CartCodesRestApiStubInterface;

class CartCodeRemover implements CartCodeRemoverInterface
{
    /**
     * @var \Spryker\Client\CartCodesRestApi\Zed\CartCodesRestApiStubInterface
     */
    protected $cartCodesRestApiStub;

    /**
     * @param \Spryker\Client\CartCodesRestApi\Zed\CartCodesRestApiStubInterface $cartCodesRestApiStub
     */
    public function __construct(CartCodesRestApiStubInterface $cartCodesRestApiStub)
    {
        $this->cartCodesRestApiStub = $cartCodesRestApiStub;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idDiscount
     *
     * @return \Generated\Shared\Transfer\CartCodeOperationResultTransfer
     */
    public function removeCode(QuoteTransfer $quoteTransfer, int $idDiscount): CartCodeOperationResultTransfer
    {
        return $this->cartCodesRestApiStub->removeCode(
            (new RemoveCodeRequestTransfer())
                ->setQuote($quoteTransfer)
                ->setIdDiscount($idDiscount)
        );
    }
}
