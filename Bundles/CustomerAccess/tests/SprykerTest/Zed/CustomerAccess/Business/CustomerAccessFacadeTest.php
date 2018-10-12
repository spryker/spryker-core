<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerAccess\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ContentTypeAccessTransfer;
use Generated\Shared\Transfer\CustomerAccessTransfer;
use Spryker\Zed\CustomerAccess\Business\CustomerAccessBusinessFactory;
use Spryker\Zed\CustomerAccess\Business\CustomerAccessFacade;
use Spryker\Zed\CustomerAccess\CustomerAccessConfig;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group CustomerAccess
 * @group Business
 * @group Facade
 * @group CustomerAccessFacadeTest
 * Add your own group annotations below this line
 */
class CustomerAccessFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\CustomerAccess\CustomerAccessBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testGetUnrestrictedContentTypesReturnsCorrectCustomerAccessObject(): void
    {
        // Arrange
        $this->tester->haveCustomerAccess();

        // Act
        $customerTransferAccess = $this->tester->getFacade()->getUnrestrictedContentTypes();

        // Assert
        $this->assertInstanceOf(CustomerAccessTransfer::class, $customerTransferAccess);

        foreach ($customerTransferAccess->getContentTypeAccess() as $contentTypeAccess) {
            $this->assertFalse($contentTypeAccess->getIsRestricted());
        }
    }

    /**
     * @return void
     */
    public function testGetRestrictedContentTypesReturnsCorrectCustomerAccessObject(): void
    {
        // Arrange
        $this->tester->haveCustomerAccess();

        // Act
        $customerTransferAccess = $this->tester->getFacade()->getRestrictedContentTypes();

        // Assert
        $this->assertInstanceOf(CustomerAccessTransfer::class, $customerTransferAccess);

        foreach ($customerTransferAccess->getContentTypeAccess() as $contentTypeAccess) {
            $this->assertTrue($contentTypeAccess->getIsRestricted());
        }
    }

    /**
     * @return void
     */
    public function testInstallNotFails(): void
    {
        // Arrange
        $customerAccessConfigMock = $this->createCustomerAccessConfigMock();
        $customerAccessConfigMock
            ->method('getContentTypes')
            ->willReturn([
                'price',
                'order-place-submit',
                'add-to-cart',
                'wishlist',
                'shopping-list',
            ]);
        $mockedContentTypes = $customerAccessConfigMock->getContentTypes();

        $customerAccessBusinessFactory = $this->createCustomerAccessBusinessFactory();
        $customerAccessBusinessFactory->setConfig($customerAccessConfigMock);

        $customerAccessFacade = $this->createCustomerAccessFacade();
        $customerAccessFacade->setFactory($customerAccessBusinessFactory);

        // Act
        $customerAccessFacade->install();

        $installedContentTypes = $this->tester->getFacade()->getAllContentTypes()->modifiedToArray();
        $installedContentTypes = array_column($installedContentTypes['content_type_access'], 'content_type');

        // Assert
        $this->assertEquals($mockedContentTypes, $installedContentTypes);
    }

    /**
     * @return void
     */
    public function testGetAllContentTypesReturnsAllTableRows()
    {
        // Arrange
        $contentType1 = 'test content 1';
        $contentType2 = 'test content 2';
        $data = [
            [
                ContentTypeAccessTransfer::IS_RESTRICTED => true,
                ContentTypeAccessTransfer::CONTENT_TYPE => $contentType1,
            ],
            [
                ContentTypeAccessTransfer::IS_RESTRICTED => false,
                ContentTypeAccessTransfer::CONTENT_TYPE => $contentType2,
            ],
        ];

        $customerAccessTransfer = $this->tester->haveCustomerAccess(
            [
                CustomerAccessTransfer::CONTENT_TYPE_ACCESS => $data,
            ]
        );

        // Act
        $contentTypes = $this->tester->getFacade()->getAllContentTypes();

        // Assert
        foreach ($contentTypes->getContentTypeAccess() as $contentType) {
            $this->assertCustomerAccessTransferContainsContentTypeAccess($customerAccessTransfer, $contentType);
        }
    }

    /**
     * @return void
     */
    public function testUpdateUnauthenticatedCustomerAccessUpdatesCorrectContentType()
    {
        // Arrange
        $customerAccessTransfer = $this->tester->haveCustomerAccess();
        $removedContentTypeAccess = $customerAccessTransfer->getContentTypeAccess()->offsetGet(0);
        $customerAccessTransfer->getContentTypeAccess()->offsetUnset(0);

        // Act
        $this->tester->getFacade()->updateUnauthenticatedCustomerAccess($customerAccessTransfer);

        /** @var \Generated\Shared\Transfer\CustomerAccessTransfer $customerAccessTransferFromDB */
        $customerAccessTransferFromDB = $this->tester->getFacade()->getUnrestrictedContentTypes();

        foreach ($customerAccessTransferFromDB->getContentTypeAccess() as $contentTypeAccess) {
            if ($contentTypeAccess->getContentType() === $removedContentTypeAccess->getContentType()) {
                $this->assertTrue($removedContentTypeAccess->getIsRestricted());
                continue;
            }

            $this->assertFalse($contentTypeAccess->getIsRestricted());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer $customerAccessTransfer
     * @param \Generated\Shared\Transfer\ContentTypeAccessTransfer $contentTypeAccessTransfer
     *
     * @return void
     */
    protected function assertCustomerAccessTransferContainsContentTypeAccess(CustomerAccessTransfer $customerAccessTransfer, ContentTypeAccessTransfer $contentTypeAccessTransfer)
    {
        foreach ($customerAccessTransfer->getContentTypeAccess() as $contentTypeAccess) {
            if ($contentTypeAccess->getContentType() === $contentTypeAccessTransfer->getContentType()) {
                $this->assertEquals($contentTypeAccess, $contentTypeAccessTransfer);
            }
        }
    }

    /**
     * @return \Spryker\Zed\CustomerAccess\Business\CustomerAccessFacade
     */
    protected function createCustomerAccessFacade(): CustomerAccessFacade
    {
        return new CustomerAccessFacade();
    }

    /**
     * @return \Spryker\Zed\CustomerAccess\Business\CustomerAccessBusinessFactory
     */
    protected function createCustomerAccessBusinessFactory(): CustomerAccessBusinessFactory
    {
        return new CustomerAccessBusinessFactory();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\Spryker\Zed\CustomerAccess\CustomerAccessConfig
     */
    protected function createCustomerAccessConfigMock()
    {
        return $this->getMockBuilder(CustomerAccessConfig::class)->getMock();
    }
}
