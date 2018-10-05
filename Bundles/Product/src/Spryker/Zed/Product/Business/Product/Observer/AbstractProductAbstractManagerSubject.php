<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Observer;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\Product\Dependency\Facade\ProductToEventInterface;
use Spryker\Zed\Product\Dependency\ProductEvents;

abstract class AbstractProductAbstractManagerSubject
{
    /**
     * @var \Spryker\Zed\Product\Business\Product\Observer\ProductAbstractCreateObserverInterface[]
     */
    protected $beforeCreateObservers = [];

    /**
     * @var \Spryker\Zed\Product\Business\Product\Observer\ProductAbstractCreateObserverInterface[]
     */
    protected $afterCreateObservers = [];

    /**
     * @var \Spryker\Zed\Product\Business\Product\Observer\ProductAbstractUpdateObserverInterface[]
     */
    protected $beforeUpdateObservers = [];

    /**
     * @var \Spryker\Zed\Product\Business\Product\Observer\ProductAbstractUpdateObserverInterface[]
     */
    protected $afterUpdateObservers = [];

    /**
     * @var \Spryker\Zed\Product\Business\Product\Observer\ProductAbstractReadObserverInterface[]
     */
    protected $readObservers = [];

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToEventInterface|null
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\Product\Business\Product\Observer\ProductAbstractCreateObserverInterface $productAbstractCreateObserver
     *
     * @return void
     */
    public function attachBeforeCreateObserver(ProductAbstractCreateObserverInterface $productAbstractCreateObserver)
    {
        $this->beforeCreateObservers[] = $productAbstractCreateObserver;
    }

    /**
     * @param \Spryker\Zed\Product\Business\Product\Observer\ProductAbstractCreateObserverInterface $productAbstractCreateObserver
     *
     * @return void
     */
    public function attachAfterCreateObserver(ProductAbstractCreateObserverInterface $productAbstractCreateObserver)
    {
        $this->afterCreateObservers[] = $productAbstractCreateObserver;
    }

    /**
     * @param \Spryker\Zed\Product\Business\Product\Observer\ProductAbstractUpdateObserverInterface $productAbstractUpdateObserver
     *
     * @return void
     */
    public function attachBeforeUpdateObserver(ProductAbstractUpdateObserverInterface $productAbstractUpdateObserver)
    {
        $this->beforeUpdateObservers[] = $productAbstractUpdateObserver;
    }

    /**
     * @param \Spryker\Zed\Product\Business\Product\Observer\ProductAbstractUpdateObserverInterface $productAbstractUpdateObserver
     *
     * @return void
     */
    public function attachAfterUpdateObserver(ProductAbstractUpdateObserverInterface $productAbstractUpdateObserver)
    {
        $this->afterUpdateObservers[] = $productAbstractUpdateObserver;
    }

    /**
     * @param \Spryker\Zed\Product\Business\Product\Observer\ProductAbstractReadObserverInterface $productAbstractReadObserver
     *
     * @return void
     */
    public function attachReadObserver(ProductAbstractReadObserverInterface $productAbstractReadObserver)
    {
        $this->readObservers[] = $productAbstractReadObserver;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function notifyBeforeCreateObservers(ProductAbstractTransfer $productAbstractTransfer)
    {
        foreach ($this->beforeCreateObservers as $observer) {
            $productAbstractTransfer = $observer->create($productAbstractTransfer);
        }

        $this->triggerEvent(ProductEvents::PRODUCT_ABSTRACT_BEFORE_CREATE, $productAbstractTransfer);

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function notifyAfterCreateObservers(ProductAbstractTransfer $productAbstractTransfer)
    {
        foreach ($this->afterCreateObservers as $observer) {
            $productAbstractTransfer = $observer->create($productAbstractTransfer);
        }

        $this->triggerEvent(ProductEvents::PRODUCT_ABSTRACT_AFTER_CREATE, $productAbstractTransfer);

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function notifyBeforeUpdateObservers(ProductAbstractTransfer $productAbstractTransfer)
    {
        foreach ($this->beforeUpdateObservers as $observer) {
            $productAbstractTransfer = $observer->update($productAbstractTransfer);
        }

        $this->triggerEvent(ProductEvents::PRODUCT_ABSTRACT_BEFORE_UPDATE, $productAbstractTransfer);

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function notifyAfterUpdateObservers(ProductAbstractTransfer $productAbstractTransfer)
    {
        foreach ($this->afterUpdateObservers as $observer) {
            $productAbstractTransfer = $observer->update($productAbstractTransfer);
        }

        $this->triggerEvent(ProductEvents::PRODUCT_ABSTRACT_AFTER_UPDATE, $productAbstractTransfer);

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function notifyReadObservers(ProductAbstractTransfer $productAbstractTransfer)
    {
        foreach ($this->readObservers as $observer) {
            $productAbstractTransfer = $observer->read($productAbstractTransfer);
        }

        $this->triggerEvent(ProductEvents::PRODUCT_ABSTRACT_READ, $productAbstractTransfer);

        return $productAbstractTransfer;
    }

    /**
     * @param string $eventName
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return void
     */
    protected function triggerEvent($eventName, ProductAbstractTransfer $productAbstractTransfer)
    {
        if (!$this->eventFacade) {
            return;
        }
        $this->eventFacade->trigger($eventName, $productAbstractTransfer);
    }

    /**
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToEventInterface $eventService
     *
     * @return void
     */
    public function setEventFacade(ProductToEventInterface $eventService)
    {
        $this->eventFacade = $eventService;
    }
}
