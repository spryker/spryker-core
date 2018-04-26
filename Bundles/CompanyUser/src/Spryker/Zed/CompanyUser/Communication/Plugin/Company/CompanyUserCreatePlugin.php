<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUser\Communication\Plugin\Company;

use ArrayObject;
use Generated\Shared\Transfer\CompanyResponseTransfer;
use Spryker\Zed\CompanyExtension\Dependency\Plugin\CompanyPostCreatePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CompanyUser\Business\CompanyUserFacadeInterface getFacade()
 */
class CompanyUserCreatePlugin extends AbstractPlugin implements CompanyPostCreatePluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CompanyResponseTransfer $companyResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    public function postCreate(CompanyResponseTransfer $companyResponseTransfer): CompanyResponseTransfer
    {
        $companyTransfer = $companyResponseTransfer->getCompanyTransfer();
        $companyUserTransfer = $companyTransfer->getInitialUserTransfer();

        if ($companyUserTransfer === null) {
            return $companyResponseTransfer;
        }

        $companyUserTransfer->setFkCompany($companyTransfer->getIdCompany());
        $companyUserResponseTransfer = $this->getFacade()->create($companyUserTransfer);

        if ($companyUserResponseTransfer->getIsSuccessful()) {
            $companyTransfer->setInitialUserTransfer($companyUserResponseTransfer->getCompanyUser());
            $companyResponseTransfer->setCompanyTransfer($companyTransfer);

            return $companyResponseTransfer;
        }

        $companyResponseTransfer->setIsSuccessful(false);
        $companyResponseTransfer = $this->addMessagesToCompanyResponse(
            $companyUserResponseTransfer->getMessages(),
            $companyResponseTransfer
        );

        return $companyResponseTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ResponseMessageTransfer[] $messages
     * @param \Generated\Shared\Transfer\CompanyResponseTransfer $companyResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    protected function addMessagesToCompanyResponse(
        ArrayObject $messages,
        CompanyResponseTransfer $companyResponseTransfer
    ) {
        foreach ($messages as $messageTransfer) {
            $companyResponseTransfer->addMessage($messageTransfer);
        }

        return $companyResponseTransfer;
    }
}
