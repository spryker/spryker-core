<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ManualOrderEntry\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\ManualOrderEntry\Persistence\ManualOrderEntryPersistenceFactory getFactory()
 */
class ManualOrderEntryQueryContainer extends AbstractQueryContainer implements ManualOrderEntryQueryContainerInterface
{
    /**
     * @api
     *
     * @param int $idOrderSource
     *
     * @return \Orm\Zed\ManualOrderEntry\Persistence\SpyOrderSourceQuery
     */
    public function queryOrderSourceById($idOrderSource)
    {
        $query = $this->getFactory()->createOrderSourceQuery();
        $query->filterByIdOrderSource($idOrderSource);

        return $query;
    }

    /**
     * @api
     *
     * @return \Orm\Zed\ManualOrderEntry\Persistence\SpyOrderSourceQuery
     */
    public function queryOrderSource()
    {
        return $this->getFactory()->createOrderSourceQuery();
    }
}
