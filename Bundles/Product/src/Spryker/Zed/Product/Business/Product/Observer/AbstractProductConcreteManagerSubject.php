<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Observer;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Dependency\Facade\ProductToEventInterface;
use Spryker\Zed\Product\Dependency\ProductEvents;

abstract class AbstractProductConcreteManagerSubject
{
    /**
     * @var \Spryker\Zed\Product\Business\Product\Observer\ProductConcreteCreateObserverInterface[]
     */
    protected $beforeCreateObservers = [];

    /**
     * @var \Spryker\Zed\Product\Business\Product\Observer\ProductConcreteCreateObserverInterface[]
     */
    protected $afterCreateObservers = [];

    /**
     * @var \Spryker\Zed\Product\Business\Product\Observer\ProductConcreteUpdateObserverInterface[]
     */
    protected $beforeUpdateObservers = [];

    /**
     * @var \Spryker\Zed\Product\Business\Product\Observer\ProductConcreteUpdateObserverInterface[]
     */
    protected $afterUpdateObservers = [];

    /**
     * @var \Spryker\Zed\Product\Business\Product\Observer\ProductConcreteReadObserverInterface[]
     */
    protected $readObservers = [];

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToEventInterface|null
     */
    protected $eventFacade;

    /**
     * @param \Spryker\Zed\Product\Business\Product\Observer\ProductConcreteCreateObserverInterface $productConcreteCreateObserver
     *
     * @return void
     */
    public function attachBeforeCreateObserver(ProductConcreteCreateObserverInterface $productConcreteCreateObserver)
    {
        $this->beforeCreateObservers[] = $productConcreteCreateObserver;
    }

    /**
     * @param \Spryker\Zed\Product\Business\Product\Observer\ProductConcreteCreateObserverInterface $productConcreteCreateObserver
     *
     * @return void
     */
    public function attachAfterCreateObserver(ProductConcreteCreateObserverInterface $productConcreteCreateObserver)
    {
        $this->afterCreateObservers[] = $productConcreteCreateObserver;
    }

    /**
     * @param \Spryker\Zed\Product\Business\Product\Observer\ProductConcreteUpdateObserverInterface $productConcreteUpdateObserver
     *
     * @return void
     */
    public function attachBeforeUpdateObserver(ProductConcreteUpdateObserverInterface $productConcreteUpdateObserver)
    {
        $this->beforeUpdateObservers[] = $productConcreteUpdateObserver;
    }

    /**
     * @param \Spryker\Zed\Product\Business\Product\Observer\ProductConcreteUpdateObserverInterface $productConcreteUpdateObserver
     *
     * @return void
     */
    public function attachAfterUpdateObserver(ProductConcreteUpdateObserverInterface $productConcreteUpdateObserver)
    {
        $this->afterUpdateObservers[] = $productConcreteUpdateObserver;
    }

    /**
     * @param \Spryker\Zed\Product\Business\Product\Observer\ProductConcreteReadObserverInterface $productConcreteReadObserver
     *
     * @return void
     */
    public function attachReadObserver(ProductConcreteReadObserverInterface $productConcreteReadObserver)
    {
        $this->readObservers[] = $productConcreteReadObserver;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function notifyBeforeCreateObservers(ProductConcreteTransfer $productConcreteTransfer)
    {
        foreach ($this->beforeCreateObservers as $observer) {
            $productConcreteTransfer = $observer->create($productConcreteTransfer);
        }

        $this->triggerEvent(ProductEvents::PRODUCT_CONCRETE_BEFORE_CREATE, $productConcreteTransfer);

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function notifyAfterCreateObservers(ProductConcreteTransfer $productConcreteTransfer)
    {
        foreach ($this->afterCreateObservers as $observer) {
            $productConcreteTransfer = $observer->create($productConcreteTransfer);
        }

        $this->triggerEvent(ProductEvents::PRODUCT_CONCRETE_AFTER_CREATE, $productConcreteTransfer);

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function notifyBeforeUpdateObservers(ProductConcreteTransfer $productConcreteTransfer)
    {
        foreach ($this->beforeUpdateObservers as $observer) {
            $productConcreteTransfer = $observer->update($productConcreteTransfer);
        }

        $this->triggerEvent(ProductEvents::PRODUCT_CONCRETE_BEFORE_UPDATE, $productConcreteTransfer);

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function notifyAfterUpdateObservers(ProductConcreteTransfer $productConcreteTransfer)
    {
        foreach ($this->afterUpdateObservers as $observer) {
            $productConcreteTransfer = $observer->update($productConcreteTransfer);
        }

        $this->triggerEvent(ProductEvents::PRODUCT_CONCRETE_AFTER_UPDATE, $productConcreteTransfer);

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function notifyReadObservers(ProductConcreteTransfer $productConcreteTransfer)
    {
        foreach ($this->readObservers as $observer) {
            $productConcreteTransfer = $observer->read($productConcreteTransfer);
        }

        $this->triggerEvent(ProductEvents::PRODUCT_CONCRETE_READ, $productConcreteTransfer);

        return $productConcreteTransfer;
    }

    /**
     * @param string $eventName
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    protected function triggerEvent($eventName, ProductConcreteTransfer $productConcreteTransfer)
    {
        if (!$this->eventFacade) {
            return;
        }
        $this->eventFacade->trigger($eventName, $productConcreteTransfer);
    }

    /**
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToEventInterface $eventFacade
     *
     * @return void
     */
    public function setEventFacade(ProductToEventInterface $eventFacade)
    {
        $this->eventFacade = $eventFacade;
    }
}
