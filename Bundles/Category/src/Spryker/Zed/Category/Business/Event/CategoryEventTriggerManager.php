<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Event;

use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\Category\Dependency\CategoryEvents;
use Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface;

class CategoryEventTriggerManager implements CategoryEventTriggerManagerInterface
{
    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface|null
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToEventFacadeInterface|null $eventFacade
     */
    public function __construct(?CategoryToEventFacadeInterface $eventFacade = null)
    {
        $this->eventFacade = $eventFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function triggerCategoryBeforeDeleteEvent(CategoryTransfer $categoryTransfer): void
    {
        $this->triggerEvent(CategoryEvents::CATEGORY_BEFORE_DELETE, $categoryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function triggerCategoryAfterDeleteEvent(CategoryTransfer $categoryTransfer): void
    {
        $this->triggerEvent(CategoryEvents::CATEGORY_AFTER_DELETE, $categoryTransfer);
    }

    /**
     * @param string $eventName
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function triggerEvent(string $eventName, CategoryTransfer $categoryTransfer): void
    {
        if (!$this->eventFacade) {
            return;
        }

        $this->eventFacade->trigger($eventName, $categoryTransfer);
    }
}
