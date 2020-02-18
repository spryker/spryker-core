<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MerchantOms\Business\MerchantOms\MerchantOmsEventDispatcher;
use Spryker\Zed\MerchantOms\Business\MerchantOms\MerchantOmsEventDispatcherInterface;
use Spryker\Zed\MerchantOms\Business\MerchantOmsProcess\MerchantOmsProcessReader;
use Spryker\Zed\MerchantOms\Business\MerchantOmsProcess\MerchantOmsProcessReaderInterface;
use Spryker\Zed\MerchantOms\Business\MerchantOrderItem\MerchantOrderItemMapper;
use Spryker\Zed\MerchantOms\Business\MerchantOrderItem\MerchantOrderItemMapperInterface;
use Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeInterface;
use Spryker\Zed\MerchantOms\MerchantOmsDependencyProvider;

/**
 * @method \Spryker\Zed\MerchantOms\MerchantOmsConfig getConfig()
 * @method \Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface getRepository()
 */
class MerchantOmsBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\MerchantOms\Dependency\Facade\MerchantOmsToStateMachineFacadeInterface
     */
    public function getStateMachineFacade(): MerchantOmsToStateMachineFacadeInterface
    {
        return $this->getProvidedDependency(MerchantOmsDependencyProvider::FACADE_STATE_MACHINE);
    }

    /**
     * @return \Spryker\Zed\MerchantOms\Business\MerchantOrderItem\MerchantOrderItemMapperInterface
     */
    public function createMerchantOrderItemMapper(): MerchantOrderItemMapperInterface
    {
        return new MerchantOrderItemMapper();
    }

    /**
     * @return \Spryker\Zed\MerchantOms\Business\MerchantOmsProcess\MerchantOmsProcessReaderInterface
     */
    public function createMerchantOrderProcessReader(): MerchantOmsProcessReaderInterface
    {
        return new MerchantOmsProcessReader($this->getRepository(), $this->getConfig());
    }

    /**
     * @return \Spryker\Zed\MerchantOms\Business\MerchantOms\MerchantOmsEventDispatcherInterface
     */
    public function createMerchantOmsEventDispatcher(): MerchantOmsEventDispatcherInterface
    {
        return new MerchantOmsEventDispatcher(
            $this->getStateMachineFacade(),
            $this->createMerchantOrderItemMapper(),
            $this->createMerchantOrderProcessReader(),
            $this->getConfig()
        );
    }
}
