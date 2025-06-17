<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Communication\Controller;

use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SspAssetCollectionTransfer;
use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetIncludeTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerFeature\Zed\SelfServicePortal\Communication\SelfServicePortalCommunicationFactory getFactory()
 * @method \SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface getRepository()
 * @method \SprykerFeature\Zed\SelfServicePortal\Business\SelfServicePortalFacadeInterface getFacade()
 */
class AutocompleteAssetController extends AbstractController
{
    /**
     * @var string
     */
    protected const REQUEST_PARAM_TERM = 'term';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function assetAction(Request $request): JsonResponse
    {
        $term = (string)$request->query->get(static::REQUEST_PARAM_TERM, '');
        $options = $this->getAssetAutocompleteData($term);

        return $this->jsonResponse($options);
    }

    /**
     * @param string $term
     *
     * @return array<string, mixed>
     */
    protected function getAssetAutocompleteData(string $term): array
    {
        if ($term === '') {
            return ['results' => []];
        }

        $sspAssetCollectionTransfer = $this->getSspAssetCollectionBySearchTerm($term);

        return $this->formatAssetsForAutocomplete($sspAssetCollectionTransfer);
    }

    /**
     * @param string $term
     *
     * @return \Generated\Shared\Transfer\SspAssetCollectionTransfer
     */
    protected function getSspAssetCollectionBySearchTerm(string $term): SspAssetCollectionTransfer
    {
        $sspAssetCriteriaTransfer = new SspAssetCriteriaTransfer();
        $sspAssetConditionsTransfer = new SspAssetConditionsTransfer();
        $paginationTransfer = new PaginationTransfer();
        $sspAssetIncludeTransfer = new SspAssetIncludeTransfer();

        $sspAssetConditionsTransfer->setSearchText('%' . $term . '%');
        $sspAssetIncludeTransfer->setWithOwnerCompanyBusinessUnit(true);
        $paginationTransfer->setMaxPerPage(10)
            ->setPage(1);

        $sspAssetCriteriaTransfer->setSspAssetConditions($sspAssetConditionsTransfer)
            ->setPagination($paginationTransfer)
            ->setInclude($sspAssetIncludeTransfer);

        return $this->getFacade()->getSspAssetCollection($sspAssetCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetCollectionTransfer $sspAssetCollectionTransfer
     *
     * @return array<string, mixed>
     */
    protected function formatAssetsForAutocomplete(SspAssetCollectionTransfer $sspAssetCollectionTransfer): array
    {
        $autocompleteData = ['results' => []];

        foreach ($sspAssetCollectionTransfer->getSspAssets() as $sspAssetTransfer) {
            $businessUnitName = $sspAssetTransfer->getCompanyBusinessUnit() ? $sspAssetTransfer->getCompanyBusinessUnit()->getNameOrFail() : '';
            $companyName = $sspAssetTransfer->getCompanyBusinessUnit() && $sspAssetTransfer->getCompanyBusinessUnit()->getCompany()
                ? $sspAssetTransfer->getCompanyBusinessUnit()->getCompany()->getNameOrFail()
                : '';

            $text = sprintf(
                '%s, %s (%s, %s)',
                $sspAssetTransfer->getNameOrFail(),
                $sspAssetTransfer->getReferenceOrFail(),
                $businessUnitName,
                $companyName,
            );

            $autocompleteData['results'][] = [
                'id' => $sspAssetTransfer->getIdSspAsset(),
                'text' => $text,
            ];
        }

        return $autocompleteData;
    }
}
