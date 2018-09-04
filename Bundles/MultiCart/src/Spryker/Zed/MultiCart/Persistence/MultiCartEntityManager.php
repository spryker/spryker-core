<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiCart\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;

/**
 * @method \Spryker\Zed\MultiCart\Persistence\MultiCartPersistenceFactory getFactory()
 */
class MultiCartEntityManager extends AbstractEntityManager implements MultiCartEntityManagerInterface
{
    protected const IS_DEFAULT = 'IsDefault';

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
            ->update([static::IS_DEFAULT => false]);
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
            ->update([static::IS_DEFAULT => true]);
    }
}
