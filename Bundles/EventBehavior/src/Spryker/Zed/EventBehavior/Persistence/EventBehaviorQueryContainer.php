<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\EventBehavior\Persistence;

use DateTime;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * @method \Spryker\Zed\EventBehavior\Persistence\EventBehaviorPersistenceFactory getFactory()
 */
class EventBehaviorQueryContainer extends AbstractQueryContainer implements EventBehaviorQueryContainerInterface
{

    /**
     * @api
     *
     * @param int $processId
     *
     * @return \Orm\Zed\EventBehavior\Persistence\SpyEventBehaviorEntityChangeQuery
     */
    public function queryEntityChange($processId)
    {
        $query = $this->getFactory()
            ->createEventBehaviorEntityChangeQuery()
            ->filterByProcessId($processId)
            ->orderByIdEventBehaviorEntityChange();

        return $query;
    }

    /**
     * @api
     *
     * @param \DateTime $date
     *
     * @return \Orm\Zed\EventBehavior\Persistence\SpyEventBehaviorEntityChangeQuery
     */
    public function queryLatestEntityChange(DateTime $date)
    {
        $query = $this->getFactory()
            ->createEventBehaviorEntityChangeQuery()
            ->filterByCreatedAt($date, Criteria::LESS_THAN)
            ->orderByIdEventBehaviorEntityChange();

        return $query;
    }

}
