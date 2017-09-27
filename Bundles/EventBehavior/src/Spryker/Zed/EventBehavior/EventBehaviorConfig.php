<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\EventBehavior;

use Spryker\Shared\EventBehavior\EventBehaviorConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class EventBehaviorConfig extends AbstractBundleConfig
{

    const EVENT_ENTITY_CHANGE_TIMEOUT_MINUTE = 5;

    /**
     * @return int
     */
    public function getEventEntityChangeTimeout()
    {
        return static::EVENT_ENTITY_CHANGE_TIMEOUT_MINUTE;
    }

    /**
     * @return bool
     */
    public function getEventBehaviorTriggeringStatus()
    {
        return $this->get(EventBehaviorConstants::EVENT_BEHAVIOR_TRIGGERING_ACTIVE, false);
    }

}
