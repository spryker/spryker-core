<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Company\Business\Model;

use Generated\Shared\Transfer\CompanyResponseTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Spryker\Zed\Company\Persistence\CompanyRepositoryInterface;

class CompanyReader implements CompanyReaderInterface
{
    /**
     * @var \Spryker\Zed\Company\Persistence\CompanyRepositoryInterface
     */
    protected $companyRepository;

    public function __construct(CompanyRepositoryInterface $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    public function getCompanyById(CompanyTransfer $companyTransfer): CompanyResponseTransfer
    {
        $companyTransfer->requireIdCompany();
        $companyTransfer = $this->companyRepository->getCompanyById($companyTransfer->getIdCompany());

        $companyResponseTransfer = new CompanyResponseTransfer();
        $companyResponseTransfer->setCompanyTransfer($companyTransfer);

        return $companyResponseTransfer;
    }
}
