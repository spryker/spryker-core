<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyMailConnector\Business\Company;

use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Spryker\Zed\CompanyMailConnector\Communication\Plugin\Mail\CompanyStatusMailTypePlugin;
use Spryker\Zed\CompanyMailConnector\Dependency\Facade\CompanyMailConnectorToCompanyUserFacadeInterface;
use Spryker\Zed\CompanyMailConnector\Dependency\Facade\CompanyMailConnectorToMailFacadeInterface;

class CompanyStatusMailer implements CompanyStatusMailerInterface
{
    /**
     * @var \Spryker\Zed\CompanyMailConnector\Dependency\Facade\CompanyMailConnectorToMailFacadeInterface
     */
    protected $mailFacade;

    /**
     * @var \Spryker\Zed\CompanyMailConnector\Dependency\Facade\CompanyMailConnectorToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @param \Spryker\Zed\CompanyMailConnector\Dependency\Facade\CompanyMailConnectorToMailFacadeInterface $mailFacade
     * @param \Spryker\Zed\CompanyMailConnector\Dependency\Facade\CompanyMailConnectorToCompanyUserFacadeInterface $companyUserFacade
     */
    public function __construct(
        CompanyMailConnectorToMailFacadeInterface $mailFacade,
        CompanyMailConnectorToCompanyUserFacadeInterface $companyUserFacade
    ) {
        $this->mailFacade = $mailFacade;
        $this->companyUserFacade = $companyUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return void
     */
    public function sendCompanyStatusEmail(CompanyTransfer $companyTransfer): void
    {
        $companyUserTransfer = $this->companyUserFacade->findInitialCompanyUserByCompanyId($companyTransfer->getIdCompany());

        if (!$companyUserTransfer) {
            return;
        }

        $mailTransfer = $this->prepareMailTransfer($companyTransfer, $companyUserTransfer);
        $this->mailFacade->handleMail($mailTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\MailTransfer
     */
    protected function prepareMailTransfer(
        CompanyTransfer $companyTransfer,
        CompanyUserTransfer $companyUserTransfer
    ): MailTransfer {
        $mailTransfer = new MailTransfer();
        $mailTransfer->setType(CompanyStatusMailTypePlugin::MAIL_TYPE)
            ->setCustomer($companyUserTransfer->getCustomer())
            ->setCompany($companyTransfer);

        return $mailTransfer;
    }
}
