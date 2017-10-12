<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\EventBehavior\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\EventBehavior\Business\EventBehaviorBusinessFactory getFactory()
 */
class EventBehaviorFacade extends AbstractFacade implements EventBehaviorFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function triggerRuntimeEvents()
    {
        $this->getFactory()->createTriggerManager()->triggerRuntimeEvents();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return void
     */
    public function triggerLostEvents()
    {
        $this->getFactory()->createTriggerManager()->triggerLostEvents();
    }
}
