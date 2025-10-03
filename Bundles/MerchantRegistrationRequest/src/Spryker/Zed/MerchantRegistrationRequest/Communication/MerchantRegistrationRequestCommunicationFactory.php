<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRegistrationRequest\Communication;

use Generated\Shared\Transfer\MerchantRegistrationRequestTransfer;
use Orm\Zed\MerchantRegistrationRequest\Persistence\SpyMerchantRegistrationRequestQuery;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantRegistrationRequest\Communication\Form\AcceptMerchantRegistrationRequestForm;
use Spryker\Zed\MerchantRegistrationRequest\Communication\Form\RejectMerchantRegistrationRequestForm;
use Spryker\Zed\MerchantRegistrationRequest\Communication\Table\MerchantRegistrationRequestTable;
use Spryker\Zed\MerchantRegistrationRequest\Dependency\Service\MerchantRegistrationRequestGuiToUtilDateTimeServiceInterface;
use Spryker\Zed\MerchantRegistrationRequest\MerchantRegistrationRequestDependencyProvider;
use Symfony\Component\Form\FormInterface;

/**
 * @method \Spryker\Zed\MerchantRegistrationRequest\Business\MerchantRegistrationRequestFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantRegistrationRequest\Persistence\MerchantRegistrationRequestEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\MerchantRegistrationRequest\Persistence\MerchantRegistrationRequestRepositoryInterface getRepository()
 * @method \Spryker\Zed\MerchantRegistrationRequest\MerchantRegistrationRequestConfig getConfig()
 */
class MerchantRegistrationRequestCommunicationFactory extends AbstractCommunicationFactory
{
    public function createMerchantRegistrationRequestTable(): MerchantRegistrationRequestTable
    {
        return new MerchantRegistrationRequestTable(
            $this->getSpyMerchantRegistrationRequestQuery(),
            $this->getUtilDateTimeService(),
            $this->getConfig(),
        );
    }

    public function getSpyMerchantRegistrationRequestQuery(): SpyMerchantRegistrationRequestQuery
    {
        return SpyMerchantRegistrationRequestQuery::create();
    }

    public function getUtilDateTimeService(): MerchantRegistrationRequestGuiToUtilDateTimeServiceInterface
    {
        return $this->getProvidedDependency(MerchantRegistrationRequestDependencyProvider::SERVICE_UTIL_DATE_TIME);
    }

    public function createAcceptMerchantRegistrationRequestForm(MerchantRegistrationRequestTransfer $merchantRegistrationRequestTransfer): FormInterface
    {
        return $this->getFormFactory()->create(AcceptMerchantRegistrationRequestForm::class, $merchantRegistrationRequestTransfer);
    }

    public function createRejectMerchantRegistrationRequestForm(): FormInterface
    {
        return $this->getFormFactory()->create(RejectMerchantRegistrationRequestForm::class);
    }
}
