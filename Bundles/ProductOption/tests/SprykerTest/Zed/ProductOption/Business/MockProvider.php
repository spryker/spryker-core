<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Business;

use Codeception\Test\Unit;
use Spryker\Zed\ProductOption\Business\OptionGroup\AbstractProductOptionSaverInterface;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValuePriceHydratorInterface;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValuePriceSaverInterface;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValueSaverInterface;
use Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaverInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToEventFacadeInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryFacadeInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleFacadeInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchFacadeInterface;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class MockProvider extends Unit
{
    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryFacadeInterface
     */
    protected function createGlossaryFacadeMock()
    {
        return $this->getMockBuilder(ProductOptionToGlossaryFacadeInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleFacadeInterface
     */
    protected function createLocaleFacadeMock()
    {
        return $this->getMockBuilder(ProductOptionToLocaleFacadeInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface
     */
    protected function createProductOptionQueryContainerMock()
    {
        return $this->getMockBuilder(ProductOptionQueryContainerInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchFacadeInterface
     */
    protected function createTouchFacadeMock()
    {
        return $this->getMockBuilder(ProductOptionToTouchFacadeInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToEventFacadeInterface
     */
    protected function createEventFacadeMock()
    {
        return $this->getMockBuilder(ProductOptionToEventFacadeInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected function createSalesContainerMock()
    {
        return $this->getMockBuilder(SalesQueryContainerInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductOption\Business\OptionGroup\AbstractProductOptionSaverInterface
     */
    protected function createAbstractOptionGroupSaverMock()
    {
        return $this->getMockBuilder(AbstractProductOptionSaverInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValueSaverInterface
     */
    protected function createProductOptionValueSaverMock()
    {
        return $this->getMockBuilder(ProductOptionValueSaverInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaverInterface
     */
    protected function createTranslationSaverMock()
    {
        return $this->getMockBuilder(TranslationSaverInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValuePriceHydratorInterface
     */
    protected function createProductOptionValuePriceHydratorMock()
    {
        return $this->getMockBuilder(ProductOptionValuePriceHydratorInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValuePriceSaverInterface
     */
    protected function createProductOptionValuePriceSaverMock()
    {
        return $this->getMockBuilder(ProductOptionValuePriceSaverInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
