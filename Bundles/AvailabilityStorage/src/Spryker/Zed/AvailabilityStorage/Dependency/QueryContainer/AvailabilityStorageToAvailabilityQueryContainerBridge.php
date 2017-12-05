<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityStorage\Dependency\QueryContainer;

class AvailabilityStorageToAvailabilityQueryContainerBridge implements AvailabilityStorageToAvailabilityQueryContainerInterface
{

    /**
     * @var \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface
     */
    protected $availabilityQueryContainer;

    /**
     * @param \Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface $availabilityQueryContainer
     */
    public function __construct($availabilityQueryContainer)
    {
        $this->availabilityQueryContainer = $availabilityQueryContainer;
    }

    /**
     * @api
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery
     */
    public function queryAllAvailabilityAbstracts()
    {
        return $this->availabilityQueryContainer->queryAllAvailabilityAbstracts();
    }

}
