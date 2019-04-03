<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ResourceShare\Business;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\ResourceShareTransfer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group ResourceShare
 * @group Business
 * @group Facade
 * @group ResourceShareFacadeTest
 * Add your own group annotations below this line
 */
class ResourceShareFacadeTest extends Test
{
    /**
     * @var \SprykerTest\Zed\ResourceShare\ResourceShareBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGenerateResourceShareShouldGenerateResourceWhenResourceTypeAndResourceDataAreUnique(): void
    {
        $resourceShareTransfer = (new ResourceShareTransfer())
            ->setResourceType('Something')
            ->setResourceData('Something else')
            ->setCustomerReference('Customer Reference');

        $resourceShareResponseTransfer = $this->getFacade()->generateResourceShare($resourceShareTransfer);

        $this->assertTrue($resourceShareResponseTransfer->getIsSuccessful());
        $this->assertNotNull($resourceShareResponseTransfer->getResourceShare());

        // Arrange

        // Act

        // Assert
    }

    /**
     * @return void
     */
    public function testGenerateResourceShareShouldAddErrorMessageWhenResourceTypeIsMissing(): void
    {
        // Arrange

        // Act

        // Assert
    }

    /**
     * @return void
     */
    public function testGenerateResourceShareShouldAddErrorMessageWhenCustomerReferenceIsMissing(): void
    {
        // Arrange

        // Act

        // Assert
    }

    /**
     * @return void
     */
    public function testGenerateResourceShareShouldAddErrorMessageWhenResourceIsAlreadyShared(): void
    {
        // Arrange

        // Act

        // Assert
    }

    /**
     * @return \Spryker\Zed\ResourceShare\Business\ResourceShareFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getFacade()
    {
        return $this->tester->getFacade();
    }
}
