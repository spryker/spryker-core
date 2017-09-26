<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Dependency;

interface AvailabilityEvents
{

    const AVAILABILITY_PUBLISH = 'Availability.abstract.publish';
    const AVAILABILITY_UNPUBLISH = 'Availability.abstract.unpublish';

    const ENTITY_SPY_AVAILABILITY_ABSTRACT_CREATE = 'Entity.spy_availability_abstract.create';
    const ENTITY_SPY_AVAILABILITY_ABSTRACT_UPDATE = 'Entity.spy_availability_abstract.update';
    const ENTITY_SPY_AVAILABILITY_ABSTRACT_DELETE = 'Entity.spy_availability_abstract.delete';

}
