<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\GiftCard\Persistence\GiftCardPersistenceFactory getFactory()
 */
class GiftCardQueryContainer extends AbstractQueryContainer implements GiftCardQueryContainerInterface
{

    /**
     * @api
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyGiftCardQuery
     */
    public function queryGiftCards()
    {
        return $this
            ->getFactory()
            ->createGiftCardQuery();
    }

    /**
     * @api
     *
     * @param int $idGiftCard
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyGiftCardQuery
     */
    public function queryGiftCardById($idGiftCard)
    {
        return $this
            ->queryGiftCards()
            ->filterByIdGiftCard($idGiftCard);
    }

}
