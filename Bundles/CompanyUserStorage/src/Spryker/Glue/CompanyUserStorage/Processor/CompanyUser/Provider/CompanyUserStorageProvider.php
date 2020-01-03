<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUserStorage\Processor\CompanyUser\Provider;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Client\CompanyUserStorage\CompanyUserStorageClientInterface;
use Spryker\Glue\CompanyUserStorage\Processor\CompanyUser\Mapper\CompanyUserStorageMapperInterface;

class CompanyUserStorageProvider implements CompanyUserStorageProviderInterface
{
    protected const MAPPING_TYPE_UUID = 'uuid';

    /**
     * @var \Spryker\Client\CompanyUserStorage\CompanyUserStorageClientInterface
     */
    protected $companyUserStorageClient;

    /**
     * @var \Spryker\Glue\CompanyUserStorage\Processor\CompanyUser\Mapper\CompanyUserStorageMapperInterface
     */
    protected $companyUserStorageMapper;

    /**
     * @param \Spryker\Client\CompanyUserStorage\CompanyUserStorageClientInterface $companyUserStorageClient
     * @param \Spryker\Glue\CompanyUserStorage\Processor\CompanyUser\Mapper\CompanyUserStorageMapperInterface $companyUserStorageMapper
     */
    public function __construct(
        CompanyUserStorageClientInterface $companyUserStorageClient,
        CompanyUserStorageMapperInterface $companyUserStorageMapper
    ) {
        $this->companyUserStorageClient = $companyUserStorageClient;
        $this->companyUserStorageMapper = $companyUserStorageMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    public function provideCompanyUserFromStorage(CompanyUserTransfer $companyUserTransfer): CompanyUserTransfer
    {
        $companyUserStorageTransfer = $this->companyUserStorageClient->findCompanyUserByMapping(
            static::MAPPING_TYPE_UUID,
            $companyUserTransfer->getUuid()
        );

        if (!$companyUserStorageTransfer) {
            return new CompanyUserTransfer();
        }

        return $this->companyUserStorageMapper->mapCompanyUserStorageTransferToCompanyUserTransfer(
            $companyUserStorageTransfer,
            $companyUserTransfer
        );
    }
}
