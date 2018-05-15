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

        if ($companyTransfer->getInitialUserTransfer() !== null) {
            $companyUserTransfer = $companyTransfer->getInitialUserTransfer();
            $companyUserTransfer->setFkCompany($companyTransfer->getIdCompany());
            $companyUserResponseTransfer = $this->getFacade()->createInitialCompanyUser($companyUserTransfer);

            $companyResponseTransfer
                ->getCompanyTransfer()
                ->setInitialUserTransfer(
                    $companyUserResponseTransfer->getCompanyUser()
                );

            if ($companyUserResponseTransfer->getIsSuccessful() !== true) {
                $companyResponseTransfer->setIsSuccessful(false);
                $this->addMessagesToCompanyResponse(
                    $companyUserResponseTransfer->getMessages(),
                    $companyResponseTransfer
                );
            }
        }

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
    ): CompanyResponseTransfer {
        foreach ($messages as $messageTransfer) {
            $companyResponseTransfer->addMessage($messageTransfer);
        }

        return $companyResponseTransfer;
    }
}
