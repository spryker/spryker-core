<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Mapper;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\RestSspInquiriesAttributesTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Generated\Shared\Transfer\SspInquiryCollectionRequestTransfer;
use Generated\Shared\Transfer\SspInquiryConditionsTransfer;
use Generated\Shared\Transfer\SspInquiryCriteriaTransfer;
use Generated\Shared\Transfer\SspInquiryIncludeTransfer;
use Generated\Shared\Transfer\SspInquiryOwnerConditionGroupTransfer;
use Generated\Shared\Transfer\SspInquiryTransfer;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class SspInquiriesMapper implements SspInquiriesMapperInterface
{
    public function __construct(protected StoreClientInterface $storeClient)
    {
    }

    public function mapRestRequestToSspInquiryCriteriaTransfer(
        RestRequestInterface $restRequest
    ): SspInquiryCriteriaTransfer {
        /** @var \Generated\Shared\Transfer\RestUserTransfer $restUserTransfer */
        $restUserTransfer = $restRequest->getRestUser();

        $sspInquiryCriteriaTransfer = (new SspInquiryCriteriaTransfer())->setSspInquiryConditions(
            (new SspInquiryConditionsTransfer())->setSspInquiryOwnerConditionGroup(
                (new SspInquiryOwnerConditionGroupTransfer())->setCompanyUser(
                    (new CompanyUserTransfer())
                        ->setIdCompanyUser($restUserTransfer->getIdCompanyUser())
                        ->setFkCompany($restUserTransfer->getIdCompany())
                    ->setFkCompanyBusinessUnit($restUserTransfer->getIdCompanyBusinessUnit()),
                ),
            ),
        );

        $sspInquiryCriteriaTransfer = $this->mapRestRequestPageToRequestParameters($restRequest, $sspInquiryCriteriaTransfer);

        if ($restRequest->getResource()->getId()) {
            $sspInquiryCriteriaTransfer->setSspInquiryConditions(
                (new SspInquiryConditionsTransfer())
                    ->setReferences([$restRequest->getResource()->getId()]),
            );
        }

        $sspInquiryCriteriaTransfer->setInclude(
            (new SspInquiryIncludeTransfer())
                ->setWithSspAsset(true)
                ->setWithOrder(true),
        );

        return $sspInquiryCriteriaTransfer;
    }

    public function mapRestSspInquiriesAttributesToSspInquiryCollectionRequestTransfer(
        RestSspInquiriesAttributesTransfer $restSspInquiriesAttributesTransfer,
        RestRequestInterface $restRequest
    ): SspInquiryCollectionRequestTransfer {
        /** @var \Generated\Shared\Transfer\RestUserTransfer $restUserTransfer */
        $restUserTransfer = $restRequest->getRestUser();
        $companyUserTransfer = (new CompanyUserTransfer())
            ->setIdCompanyUser($restUserTransfer->getIdCompanyUser())
            ->setFkCompany($restUserTransfer->getIdCompany())
            ->setFkCompanyBusinessUnit($restUserTransfer->getIdCompanyBusinessUnit());

        $sspInquiryTransfer = (new SspInquiryTransfer())
            ->fromArray($restSspInquiriesAttributesTransfer->toArray(), true)
            ->setSspAsset((new SspAssetTransfer())->setReference($restSspInquiriesAttributesTransfer->getSspAssetReference()))
            ->setCompanyUser($companyUserTransfer)
            ->setOrder((new OrderTransfer())->setOrderReference($restSspInquiriesAttributesTransfer->getOrderReference()));

        $sspInquiryCollectionRequestTransfer = (new SspInquiryCollectionRequestTransfer())->addSspInquiry($sspInquiryTransfer);

        $sspInquiryCollectionRequestTransfer->setCompanyUser($companyUserTransfer);

        return $sspInquiryCollectionRequestTransfer;
    }

    public function mapSspInquiryTransferToRestSspInquiriesAttributesTransfer(
        SspInquiryTransfer $sspInquiryTransfer
    ): RestSspInquiriesAttributesTransfer {
        return (new RestSspInquiriesAttributesTransfer())
            ->fromArray($sspInquiryTransfer->toArray(), true)
            ->setSspAssetReference($sspInquiryTransfer->getSspAsset()?->getReference())
            ->setOrderReference($sspInquiryTransfer->getOrder()?->getOrderReference());
    }

    protected function mapRestRequestPageToRequestParameters(
        RestRequestInterface $restRequest,
        SspInquiryCriteriaTransfer $sspInquiryCriteriaTransfer
    ): SspInquiryCriteriaTransfer {
        if ($restRequest->getPage() === null) {
            return $sspInquiryCriteriaTransfer;
        }

        $sspInquiryCriteriaTransfer->setPagination(
            (new PaginationTransfer())
                ->setOffset($restRequest->getPage()->getOffset())
                ->setLimit($restRequest->getPage()->getLimit()),
        );

        return $sspInquiryCriteriaTransfer;
    }
}
