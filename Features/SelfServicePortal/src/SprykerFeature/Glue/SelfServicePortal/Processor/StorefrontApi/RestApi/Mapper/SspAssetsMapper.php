<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Processor\StorefrontApi\RestApi\Mapper;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\RestSspAssetsAttributesTransfer;
use Generated\Shared\Transfer\SspAssetBusinessUnitAssignmentTransfer;
use Generated\Shared\Transfer\SspAssetCollectionRequestTransfer;
use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class SspAssetsMapper implements SspAssetsMapperInterface
{
    public function mapRestRequestToSspAssetCriteriaTransfer(
        RestRequestInterface $restRequest
    ): SspAssetCriteriaTransfer {
        /** @var \Generated\Shared\Transfer\RestUserTransfer $restUserTransfer */
        $restUserTransfer = $restRequest->getRestUser();

        $sspAssetCriteriaTransfer = (new SspAssetCriteriaTransfer())->setCompanyUser(
            (new CompanyUserTransfer())
                ->setIdCompanyUser($restUserTransfer->getIdCompanyUser())
                ->setFkCompany($restUserTransfer->getIdCompany())
            ->setFkCompanyBusinessUnit($restUserTransfer->getIdCompanyBusinessUnit()),
        );

        $sspAssetCriteriaTransfer = $this->mapRestRequestPageToRequestParameters($restRequest, $sspAssetCriteriaTransfer);

        if ($restRequest->getResource()->getId()) {
            $sspAssetCriteriaTransfer->setSspAssetConditions(
                (new SspAssetConditionsTransfer())
                    ->setReferences([$restRequest->getResource()->getId()]),
            );
        }

        return $sspAssetCriteriaTransfer;
    }

    public function mapRestRequestToSspAssetCollectionRequestTransfer(
        RestRequestInterface $restRequest,
        RestSspAssetsAttributesTransfer $restSspAssetsAttributesTransfer
    ): SspAssetCollectionRequestTransfer {
        /** @var \Generated\Shared\Transfer\RestUserTransfer $restUserTransfer */
        $restUserTransfer = $restRequest->getRestUser();
        $restUserCompanyBusinessUnitTransfer = (new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit($restUserTransfer->getIdCompanyBusinessUnit());

        $sspAssetTransfer = (new SspAssetTransfer())
            ->fromArray($restSspAssetsAttributesTransfer->toArray(), true)
            ->setCompanyBusinessUnit($restUserCompanyBusinessUnitTransfer);

        $sspAssetTransfer->addBusinessUnitAssignment(
            (new SspAssetBusinessUnitAssignmentTransfer())->setCompanyBusinessUnit($restUserCompanyBusinessUnitTransfer),
        );

        foreach ($restSspAssetsAttributesTransfer->getBusinessUnitAssignments() as $businessUnitAssignment) {
            $sspAssetTransfer->addBusinessUnitAssignment(
                (new SspAssetBusinessUnitAssignmentTransfer())->setCompanyBusinessUnit(
                    (new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit($businessUnitAssignment->getIdCompanyBusinessUnitOrFail()),
                ),
            );
        }

        $sspAssetCollectionRequestTransfer = (new SspAssetCollectionRequestTransfer())->addSspAsset($sspAssetTransfer);

        $sspAssetCollectionRequestTransfer->setCompanyUser(
            (new CompanyUserTransfer())
                ->setIdCompanyUser($restUserTransfer->getIdCompanyUser())
                ->setFkCompany($restUserTransfer->getIdCompany())
            ->setFkCompanyBusinessUnit($restUserTransfer->getIdCompanyBusinessUnit()),
        );

        return $sspAssetCollectionRequestTransfer;
    }

    protected function mapRestRequestPageToRequestParameters(
        RestRequestInterface $restRequest,
        SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
    ): SspAssetCriteriaTransfer {
        if ($restRequest->getPage() === null) {
            return $sspAssetCriteriaTransfer;
        }

        $sspAssetCriteriaTransfer->setPagination(
            (new PaginationTransfer())
                ->setOffset($restRequest->getPage()->getOffset())
                ->setLimit($restRequest->getPage()->getLimit()),
        );

        return $sspAssetCriteriaTransfer;
    }
}
