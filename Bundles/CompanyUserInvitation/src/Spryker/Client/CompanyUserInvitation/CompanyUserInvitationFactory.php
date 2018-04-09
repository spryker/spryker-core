<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUserInvitation;

use Spryker\Client\CompanyUserInvitation\Model\Mapper\InvitationMapper;
use Spryker\Client\CompanyUserInvitation\Model\Mapper\InvitationMapperInterface;
use Spryker\Client\CompanyUserInvitation\Model\Reader\CsvInvitationReader;
use Spryker\Client\CompanyUserInvitation\Model\Reader\InvitationReaderInterface;
use Spryker\Client\CompanyUserInvitation\Zed\CompanyUserInvitationStub;
use Spryker\Client\CompanyUserInvitation\Zed\CompanyUserInvitationStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CompanyUserInvitationFactory extends AbstractFactory
{
    /**
     * @param string $filePath
     *
     * @return \Spryker\Client\CompanyUserInvitation\Zed\CompanyUserInvitationStubInterface
     */
    public function createZedCompanyUserInvitationStub(string $filePath): CompanyUserInvitationStubInterface
    {
        return new CompanyUserInvitationStub(
            $this->createCsnInvitationReader($filePath),
            $this->createInvitationMapper(),
            $this->getZedRequestClient(),
            $this->getCustomerClient()
        );
    }

    /**
     * @param string $filePath
     *
     * @return \Spryker\Client\CompanyUserInvitation\Model\Reader\InvitationReaderInterface
     */
    protected function createCsnInvitationReader(string $filePath): InvitationReaderInterface
    {
        return new CsvInvitationReader($filePath);
    }

    /**
     * @return \Spryker\Client\CompanyUserInvitation\Model\Mapper\InvitationMapperInterface
     */
    protected function createInvitationMapper(): InvitationMapperInterface
    {
        return new InvitationMapper();
    }

    /**
     * @return \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected function getZedRequestClient()
    {
        return $this->getProvidedDependency(CompanyUserInvitationDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \Spryker\Client\CompanyUserInvitation\Dependency\Client\CompanyUserInvitationToCustomerClientBridgeInterface
     */
    protected function getCustomerClient()
    {
        return $this->getProvidedDependency(CompanyUserInvitationDependencyProvider::CLIENT_CUSTOMER);
    }
}
