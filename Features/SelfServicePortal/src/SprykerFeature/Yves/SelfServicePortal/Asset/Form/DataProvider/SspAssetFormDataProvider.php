<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Asset\Form\DataProvider;

use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetIncludeTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface;
use SprykerFeature\Yves\SelfServicePortal\Asset\Form\SspAssetForm;
use SprykerFeature\Yves\SelfServicePortal\Plugin\Router\SelfServicePortalPageRouteProviderPlugin;
use SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig;

class SspAssetFormDataProvider
{
    /**
     * @param \SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface $sspAssetManagementClient
     * @param \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig $config
     */
    public function __construct(
        protected SelfServicePortalClientInterface $sspAssetManagementClient,
        protected SelfServicePortalConfig $config
    ) {
    }

    /**
     * @param string $sspAssetReference
     *
     * @return \Generated\Shared\Transfer\SspAssetTransfer|null
     */
    public function getData(string $sspAssetReference): ?SspAssetTransfer
    {
        $sspAssetCollectionTransfer = $this->sspAssetManagementClient->getSspAssetCollection(
            (new SspAssetCriteriaTransfer())
                ->setSspAssetConditions(
                    (new SspAssetConditionsTransfer())
                        ->addReference($sspAssetReference),
                )
                ->setInclude(
                    (new SspAssetIncludeTransfer())
                        ->setWithOwnerCompanyBusinessUnit(true)
                        ->setWithAssignedBusinessUnits(true),
                ),
        );

        if ($sspAssetCollectionTransfer->getSspAssets()->count() === 0) {
            return null;
        }

        return $sspAssetCollectionTransfer->getSspAssets()->getIterator()->current();
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return array<string, mixed>
     */
    public function getOptions(SspAssetTransfer $sspAssetTransfer): array
    {
        return [
            SspAssetForm::OPTION_ORIGINAL_IMAGE_URL => $sspAssetTransfer->getImage() ? $this->getAssetImageUrl($sspAssetTransfer) : null,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\SspAssetTransfer $sspAssetTransfer
     *
     * @return string
     */
    protected function getAssetImageUrl(SspAssetTransfer $sspAssetTransfer): string
    {
        $sspAssetImagePath = Url::generate(SelfServicePortalPageRouteProviderPlugin::ROUTE_NAME_ASSET_VIEW_IMAGE, ['ssp-asset-reference' => $sspAssetTransfer->getReferenceOrFail()])->build();

        return sprintf('%s/%s', $this->config->getYvesBaseUrl(), $sspAssetImagePath);
    }
}
