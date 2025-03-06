<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Client\SspInquiryManagement;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;
use SprykerFeature\Client\SspInquiryManagement\Zed\SspInquiryManagementStub;
use SprykerFeature\Client\SspInquiryManagement\Zed\SspInquiryManagementStubInterface;

class SspInquiryManagementFactory extends AbstractFactory
{
    /**
     * @return \SprykerFeature\Client\SspInquiryManagement\Zed\SspInquiryManagementStubInterface
     */
    public function createSspInquiryManagementStub(): SspInquiryManagementStubInterface
    {
        return new SspInquiryManagementStub(
            $this->getZedRequestClient(),
        );
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    public function getZedRequestClient(): ZedRequestClientInterface
    {
        return $this->getProvidedDependency(SspInquiryManagementDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
