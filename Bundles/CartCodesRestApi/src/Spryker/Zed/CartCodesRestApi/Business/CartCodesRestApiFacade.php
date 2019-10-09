<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CartCodesRestApi\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CartCodesRestApi\Business\CartCodesRestApiBusinessFactory getFactory()
 * @method \Spryker\Zed\CartCodesRestApi\Persistence\CartCodesRestApiRepositoryInterface getRepository()
 * @method \Spryker\Zed\CartCodesRestApi\Persistence\CartCodesRestApiEntityManagerInterface getEntityManager()
 */
class CartCodesRestApiFacade extends AbstractFacade implements CartCodesRestApiFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addCandidate(QuoteTransfer $quoteTransfer, string $code): QuoteTransfer
    {
        // TODO: Implement addCandidate() method.
    }
}
