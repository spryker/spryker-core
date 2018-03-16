<?php

namespace SprykerTest\Zed\CustomerAccess\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\ContentTypeAccessTransfer;
use Generated\Shared\Transfer\CustomerAccessTransfer;

/**
 * @property \SprykerTest\Zed\CustomerAccess\CustomerAccessBusinessTester $tester
 */
class CustomerAccessFacadeTest extends Unit
{
    /**
     * @return void
     */
    public function testFindUnauthenticatedCustomerAccessReturnsCorrectCustomerAccessObject()
    {
        // Arrange
        $this->tester->haveCustomerAccess();

        // Act
        $customerTransferAccess = $this->tester->getFacade()->findUnauthenticatedCustomerAccess();

        // Assert
        $this->assertInstanceOf(CustomerAccessTransfer::class, $customerTransferAccess);
    }

    public function testFindAllContentTypesReturnsAllTableRows()
    {
        // Arrange
        $contentType1 = 'test content 1';
        $contentType2 = 'test content 2';
        $data = [
            [
                ContentTypeAccessTransfer::CAN_ACCESS => true,
                ContentTypeAccessTransfer::CONTENT_TYPE => $contentType1,
            ],
            [
                ContentTypeAccessTransfer::CAN_ACCESS => false,
                ContentTypeAccessTransfer::CONTENT_TYPE => $contentType2,
            ],
        ];

        $customerAccessTransfer = $this->tester->haveCustomerAccess(
            [
                CustomerAccessTransfer::CONTENT_TYPE_ACCESS => $data,
            ]
        );

        // Act
        $contentTypes = $this->tester->getFacade()->findAllContentTypes();

        // Assert
        foreach ($contentTypes as $contentType) {
            $this->assertCustomerAccessTransferContainsContentTypeAccess($customerAccessTransfer, $contentType);
        }
    }

    public function testUpdateOnlyContentTypeToAccessibleUpdatesCorrectContentType()
    {
        // Arrange
        $customerAccessTransfer = $this->tester->haveCustomerAccess();
        $removedContentTypeAccess = $customerAccessTransfer->getContentTypeAccess()->offsetGet(0);
        $customerAccessTransfer->getContentTypeAccess()->offsetUnset(0);

        // Act
        $this->tester->getFacade()->updateOnlyContentTypesToAccessible($customerAccessTransfer);

        /** @var CustomerAccessTransfer $customerAccessTransferFromDB */
        $customerAccessTransferFromDB = $this->tester->getFacade()->findUnauthenticatedCustomerAccess();

        foreach($customerAccessTransferFromDB->getContentTypeAccess() as $contentTypeAccess) {
            if($contentTypeAccess->getContentType() === $removedContentTypeAccess->getContentType()) {
                $this->assertFalse($removedContentTypeAccess->getCanAccess());
                continue;
            }

            $this->assertTrue($contentTypeAccess->getCanAccess());
        }

    }

    protected function assertCustomerAccessTransferContainsContentTypeAccess(CustomerAccessTransfer $customerAccessTransfer, ContentTypeAccessTransfer $contentTypeAccessTransfer)
    {
        foreach($customerAccessTransfer->getContentTypeAccess() as $contentTypeAccess) {
            if($contentTypeAccess->getContentType() === $contentTypeAccessTransfer) {
                $this->assertSame($contentTypeAccess, $contentTypeAccessTransfer);
            }
        }
    }
}
