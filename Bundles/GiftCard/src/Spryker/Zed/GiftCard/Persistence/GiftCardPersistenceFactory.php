<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Persistence;

use Orm\Zed\GiftCard\Persistence\SpyGiftCardQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainer getQueryContainer()
 * @method \Spryker\Zed\GiftCard\GiftCardConfig getConfig()
 */
class GiftCardPersistenceFactory extends AbstractPersistenceFactory
{

    /**
     * @return \Orm\Zed\GiftCard\Persistence\SpyGiftCardQuery
     */
    public function createGiftCardQuery()
    {
        return SpyGiftCardQuery::create();
    }

}
