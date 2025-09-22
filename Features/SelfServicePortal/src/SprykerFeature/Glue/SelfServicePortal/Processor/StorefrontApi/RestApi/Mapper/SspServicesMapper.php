<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SspServiceCriteriaTransfer;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class SspServicesMapper implements SspServicesMapperInterface
{
    public function __construct(protected StoreClientInterface $storeClient)
    {
    }

    public function mapRestRequestToSspServiceCriteriaTransfer(
        RestRequestInterface $restRequest
    ): SspServiceCriteriaTransfer {
        /** @var \Generated\Shared\Transfer\RestUserTransfer $restUserTransfer */
        $restUserTransfer = $restRequest->getRestUser();

        $customerTransfer = (new CustomerTransfer())
            ->setIdCustomer($restUserTransfer->getSurrogateIdentifier())
            ->setCustomerReference($restUserTransfer->getNaturalIdentifier());

        $sspServiceCriteriaTransfer = (new SspServiceCriteriaTransfer())->setCompanyUser(
            (new CompanyUserTransfer())
                ->setIdCompanyUser($restUserTransfer->getIdCompanyUser())
                ->setCompany(
                    (new CompanyTransfer())->setIdCompany($restUserTransfer->getIdCompany()),
                )
                ->setCompanyBusinessUnit(
                    (new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit($restUserTransfer->getIdCompanyBusinessUnit()),
                )
                ->setCustomer($customerTransfer),
        );

        $sspServiceCriteriaTransfer = $this->mapRestRequestPageToRequestParameters($restRequest, $sspServiceCriteriaTransfer);

        return $sspServiceCriteriaTransfer;
    }

    protected function mapRestRequestPageToRequestParameters(
        RestRequestInterface $restRequest,
        SspServiceCriteriaTransfer $sspServiceCriteriaTransfer
    ): SspServiceCriteriaTransfer {
        if ($restRequest->getPage() === null) {
            return $sspServiceCriteriaTransfer;
        }

        $sspServiceCriteriaTransfer->setPagination(
            (new PaginationTransfer())
                ->setOffset($restRequest->getPage()->getOffset())
                ->setLimit($restRequest->getPage()->getLimit()),
        );

        return $sspServiceCriteriaTransfer;
    }
}
