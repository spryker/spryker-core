<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Dependency;

use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityAbstractTableMap;

interface AvailabilityEvents
{

    const AVAILABILITY_PUBLISH = 'Availability.abstract.publish';
    const AVAILABILITY_UNPUBLISH = 'Availability.abstract.unpublish';

    const ENTITY_SPY_AVAILABILITY_ABSTRACT_CREATE = 'Entity.' . SpyAvailabilityAbstractTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_AVAILABILITY_ABSTRACT_UPDATE = 'Entity.' . SpyAvailabilityAbstractTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_AVAILABILITY_ABSTRACT_DELETE = 'Entity.' . SpyAvailabilityAbstractTableMap::TABLE_NAME . '.delete';

}
