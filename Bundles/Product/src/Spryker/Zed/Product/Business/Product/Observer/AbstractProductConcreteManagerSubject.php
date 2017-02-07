<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Observer;

use Generated\Shared\Transfer\ProductConcreteTransfer;

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

        return $productConcreteTransfer;
    }

}
