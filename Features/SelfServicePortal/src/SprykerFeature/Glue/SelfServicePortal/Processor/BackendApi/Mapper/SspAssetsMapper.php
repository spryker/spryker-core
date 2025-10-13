<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Glue\SelfServicePortal\Processor\BackendApi\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use Generated\Shared\Transfer\SspAssetBusinessUnitAssignmentTransfer;
use Generated\Shared\Transfer\SspAssetCollectionRequestTransfer;
use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetIncludeTransfer;
use Generated\Shared\Transfer\SspAssetsBackendApiAttributesTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface;

class SspAssetsMapper implements SspAssetsMapperInterface
{
    public function __construct(protected SelfServicePortalFacadeInterface $selfServicePortalFacade)
    {
    }

    public function mapGlueRequestToSspAssetCriteriaTransfer(GlueRequestTransfer $glueRequestTransfer): SspAssetCriteriaTransfer
    {
        $sspAssetCriteriaTransfer = new SspAssetCriteriaTransfer();

        $sspAssetConditionsTransfer = new SspAssetConditionsTransfer();

        if ($glueRequestTransfer->getResource() && $glueRequestTransfer->getResource()->getId()) {
            $sspAssetConditionsTransfer->setReferences([$glueRequestTransfer->getResource()->getId()]);
        }

        $sspAssetCriteriaTransfer->setSspAssetConditions($sspAssetConditionsTransfer);

        $sspAssetCriteriaTransfer = $this->mapGlueRequestPaginationToSspAssetCriteria($glueRequestTransfer, $sspAssetCriteriaTransfer);
        $sspAssetCriteriaTransfer = $this->mapGlueRequestSortToSspAssetCriteria($glueRequestTransfer, $sspAssetCriteriaTransfer);

        $sspAssetCriteriaTransfer->setInclude(
            (new SspAssetIncludeTransfer())
                ->setWithOwnerCompanyBusinessUnit(true),
        );

        return $sspAssetCriteriaTransfer;
    }

    public function mapGlueRequestToSspAssetCollectionRequestTransferForCreate(GlueRequestTransfer $glueRequestTransfer): SspAssetCollectionRequestTransfer
    {
        /** @var \Generated\Shared\Transfer\SspAssetsBackendApiAttributesTransfer $sspAssetsBackendApiAttributesTransfer */
        $sspAssetsBackendApiAttributesTransfer = $glueRequestTransfer->getResourceOrFail()->getAttributes();
        $sspAssetOwnerCompanyBusinessUnitTransfer = (new CompanyBusinessUnitTransfer())->setUuid($sspAssetsBackendApiAttributesTransfer->getCompanyBusinessUnitOwnerUuid());

        $sspAssetTransfer = (new SspAssetTransfer())->fromArray($sspAssetsBackendApiAttributesTransfer->toArray(), true)
            ->setCompanyBusinessUnit($sspAssetOwnerCompanyBusinessUnitTransfer)
            ->addBusinessUnitAssignment(
                (new SspAssetBusinessUnitAssignmentTransfer())->setCompanyBusinessUnit($sspAssetOwnerCompanyBusinessUnitTransfer),
            );

        return (new SspAssetCollectionRequestTransfer())->addSspAsset($sspAssetTransfer);
    }

    public function mapGlueRequestToSspAssetCollectionRequestTransferForUpdate(GlueRequestTransfer $glueRequestTransfer): SspAssetCollectionRequestTransfer
    {
        /** @var \Generated\Shared\Transfer\SspAssetsBackendApiAttributesTransfer $sspAssetsBackendApiAttributesTransfer */
        $sspAssetsBackendApiAttributesTransfer = $glueRequestTransfer->getResourceOrFail()->getAttributes();

        $sspAssetTransfer = $this->findSspAssetByReference($glueRequestTransfer->getResourceOrFail()->getIdOrFail());

        if (!$sspAssetTransfer) {
            return (new SspAssetCollectionRequestTransfer());
        }

        $sspAssetTransfer
            ->setName($sspAssetsBackendApiAttributesTransfer->getName())
            ->setSerialNumber($sspAssetsBackendApiAttributesTransfer->getSerialNumber())
            ->setNote($sspAssetsBackendApiAttributesTransfer->getNote())
            ->setExternalImageUrl($sspAssetsBackendApiAttributesTransfer->getExternalImageUrl())
            ->setCompanyBusinessUnit(
                (new CompanyBusinessUnitTransfer())->setUuid(null), // Prevent changing the owner on update, will be handled in a separate endpoint.
            );

        return (new SspAssetCollectionRequestTransfer())->addSspAsset($sspAssetTransfer);
    }

    public function mapSspAssetTransferToSspAssetsBackendApiAttributesTransfer(
        SspAssetTransfer $sspAssetTransfer
    ): SspAssetsBackendApiAttributesTransfer {
        $attributesTransfer = (new SspAssetsBackendApiAttributesTransfer())
            ->fromArray($sspAssetTransfer->toArray(), true);

        if ($sspAssetTransfer->getCompanyBusinessUnit()) {
            $attributesTransfer->setCompanyBusinessUnitOwnerUuid(
                $sspAssetTransfer->getCompanyBusinessUnit()->getUuid(),
            );
        }

        return $attributesTransfer;
    }

    protected function mapGlueRequestPaginationToSspAssetCriteria(
        GlueRequestTransfer $glueRequestTransfer,
        SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
    ): SspAssetCriteriaTransfer {
        if (!$glueRequestTransfer->getPagination()) {
            return $sspAssetCriteriaTransfer;
        }

        $paginationTransfer = (new PaginationTransfer())
            ->setOffset($glueRequestTransfer->getPagination()->getOffset())
            ->setLimit($glueRequestTransfer->getPagination()->getLimit());

        return $sspAssetCriteriaTransfer->setPagination($paginationTransfer);
    }

    protected function mapGlueRequestSortToSspAssetCriteria(
        GlueRequestTransfer $glueRequestTransfer,
        SspAssetCriteriaTransfer $sspAssetCriteriaTransfer
    ): SspAssetCriteriaTransfer {
        $sortCollection = [];

        foreach ($glueRequestTransfer->getSortings() as $sorting) {
            $sortTransfer = (new SortTransfer())
                ->setField($sorting->getField())
                ->setIsAscending($sorting->getDirection() === 'asc');

            $sortCollection[] = $sortTransfer;
        }

        return $sspAssetCriteriaTransfer->setSortCollection(new ArrayObject($sortCollection));
    }

    protected function findSspAssetByReference(string $assetReference): ?SspAssetTransfer
    {
        $sspAssetCollectionTransfer = $this->selfServicePortalFacade->getSspAssetCollection(
            (new SspAssetCriteriaTransfer())->setSspAssetConditions(
                (new SspAssetConditionsTransfer())->setReferences([$assetReference]),
            ),
        );

        if ($sspAssetCollectionTransfer->getSspAssets()->count() === 0) {
            return null;
        }

        return $sspAssetCollectionTransfer->getSspAssets()->getIterator()->current();
    }
}
