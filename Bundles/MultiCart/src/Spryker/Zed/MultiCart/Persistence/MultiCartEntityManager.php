<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Persistence;

use Generated\Shared\Transfer\SpyQuoteEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MultiCart\Persistence\MultiCartPersistenceFactory getFactory()
 */
class MultiCartEntityManager extends AbstractEntityManager implements MultiCartEntityManagerInterface
{
    /**
     * @param string $customerReference
     *
     * @return void
     */
    public function resetQuoteDefaultFlagByCustomer(string $customerReference): void
    {
        $this->getFactory()
            ->createQuoteQuery()
            ->filterByCustomerReference($customerReference)
            ->update([ucfirst(SpyQuoteEntityTransfer::IS_DEFAULT) => false]);
    }

    /**
     * @param int $idQuote
     *
     * @return void
     */
    public function setDefaultQuote(int $idQuote): void
    {
        $this->getFactory()
            ->createQuoteQuery()
            ->filterByIdQuote($idQuote)
            ->update([ucfirst(SpyQuoteEntityTransfer::IS_DEFAULT) => true]);
    }
}
