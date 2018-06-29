<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CustomerAccess\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CustomerAccessBuilder;
use Generated\Shared\Transfer\ContentTypeAccessTransfer;
use Generated\Shared\Transfer\CustomerAccessTransfer;
use Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccessQuery;
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
                    ContentTypeAccessTransfer::IS_RESTRICTED => true,
                    ContentTypeAccessTransfer::CONTENT_TYPE => 'test content 1',
                ],
                [
                    ContentTypeAccessTransfer::IS_RESTRICTED => false,
                    ContentTypeAccessTransfer::CONTENT_TYPE => 'test content 2',
                ],
            ],
        ];

        $customerAccessTransfer = (new CustomerAccessBuilder(array_merge($data, $override)))->build();

        foreach ($customerAccessTransfer->getContentTypeAccess() as $contentType) {
            $customerAccess = SpyUnauthenticatedCustomerAccessQuery::create()
                ->filterByContentType($contentType->getContentType())
                ->findOneOrCreate();
            $customerAccess->setIsRestricted($contentType->getIsRestricted());
            $customerAccess->save();

            $contentType->fromArray($customerAccess->toArray(), true);
        }

        return $customerAccessTransfer;
    }
}
