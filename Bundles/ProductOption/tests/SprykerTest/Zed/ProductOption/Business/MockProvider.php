<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ProductOption\Business;

use Codeception\Test\Unit;
use Spryker\Zed\ProductOption\Business\OptionGroup\AbstractProductOptionSaverInterface;
use Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValueSaverInterface;
use Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaverInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface;
use Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchInterface;
use Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface;
use Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface;

class MockProvider extends Unit
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToGlossaryInterface
     */
    protected function createGlossaryFacadeMock()
    {
        return $this->getMockBuilder(ProductOptionToGlossaryInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToLocaleInterface
     */
    protected function createLocaleFacadeMock()
    {
        return $this->getMockBuilder(ProductOptionToLocaleInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductOption\Persistence\ProductOptionQueryContainerInterface
     */
    protected function createProductOptionQueryContainerMock()
    {
        return $this->getMockBuilder(ProductOptionQueryContainerInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductOption\Dependency\Facade\ProductOptionToTouchInterface
     */
    protected function createTouchFacadeMock()
    {
        return $this->getMockBuilder(ProductOptionToTouchInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface
     */
    protected function createSalesContainerMock()
    {
        return $this->getMockBuilder(SalesQueryContainerInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductOption\Business\OptionGroup\AbstractProductOptionSaverInterface
     */
    protected function createAbstractOptionGroupSaverMock()
    {
        return $this->getMockBuilder(AbstractProductOptionSaverInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductOption\Business\OptionGroup\ProductOptionValueSaverInterface
     */
    protected function createProductOptionValueSaverMock()
    {
        return $this->getMockBuilder(ProductOptionValueSaverInterface::class)
            ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\ProductOption\Business\OptionGroup\TranslationSaverInterface
     */
    protected function createTranslationSaverMock()
    {
        return $this->getMockBuilder(TranslationSaverInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
