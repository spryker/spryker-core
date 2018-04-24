<?php

namespace SprykerTest\Zed\CustomerAccess\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CustomerAccessBuilder;
use Generated\Shared\Transfer\ContentTypeAccessTransfer;
use Generated\Shared\Transfer\CustomerAccessTransfer;
use Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccess;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CustomerAccessHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function haveCustomerAccess(array $override = [])
    {
        $data = [
            CustomerAccessTransfer::CONTENT_TYPE_ACCESS => [
                [
                    ContentTypeAccessTransfer::CAN_ACCESS => true,
                    ContentTypeAccessTransfer::CONTENT_TYPE => 'test content 1',
                ],
                [
                    ContentTypeAccessTransfer::CAN_ACCESS => false,
                    ContentTypeAccessTransfer::CONTENT_TYPE => 'test content 2',
                ],
            ],
        ];

        $customerAccessTransfer = (new CustomerAccessBuilder(array_merge($data, $override)))->build();

        foreach ($customerAccessTransfer->getContentTypeAccess() as $contentType) {
            $customerAccess = new SpyUnauthenticatedCustomerAccess();
            $customerAccess->setContentType($contentType->getContentType());
            $customerAccess->setHasAccess($contentType->getHasAccess());

            $customerAccess->save();
        }

        return $customerAccessTransfer;
    }
}
