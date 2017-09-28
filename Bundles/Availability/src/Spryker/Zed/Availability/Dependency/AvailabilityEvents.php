<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Availability\Dependency;

interface AvailabilityEvents
{

    /**
     * Specification
     * - This events will be used for spy_availability_abstract entity creation
     *
     * @api
     */
    const ENTITY_SPY_AVAILABILITY_ABSTRACT_CREATE = 'Entity.spy_availability_abstract.create';

    /**
     * Specification
     * - This events will be used for spy_availability_abstract entity changes
     *
     * @api
     */
    const ENTITY_SPY_AVAILABILITY_ABSTRACT_UPDATE = 'Entity.spy_availability_abstract.update';

    /**
     * Specification
     * - This events will be used for spy_availability_abstract entity deletion
     *
     * @api
     */
    const ENTITY_SPY_AVAILABILITY_ABSTRACT_DELETE = 'Entity.spy_availability_abstract.delete';

}
