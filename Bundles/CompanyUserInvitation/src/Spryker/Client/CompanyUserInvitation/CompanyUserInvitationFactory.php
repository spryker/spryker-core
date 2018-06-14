<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUserInvitation;

use Spryker\Client\CompanyUserInvitation\Zed\CompanyUserInvitationStub;
use Spryker\Client\CompanyUserInvitation\Zed\CompanyUserInvitationStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CompanyUserInvitationFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\CompanyUserInvitation\Zed\CompanyUserInvitationStubInterface
     */
    public function createZedCompanyUserInvitationStub(): CompanyUserInvitationStubInterface
    {
        return new CompanyUserInvitationStub(
            $this->getZedRequestClient()
        );
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected function getZedRequestClient()
    {
        return $this->getProvidedDependency(CompanyUserInvitationDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
