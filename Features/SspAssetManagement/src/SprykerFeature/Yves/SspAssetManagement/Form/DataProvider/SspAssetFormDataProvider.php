<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspAssetManagement\Form\DataProvider;

use Generated\Shared\Transfer\SspAssetConditionsTransfer;
use Generated\Shared\Transfer\SspAssetCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetIncludeTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Spryker\Service\UtilText\Model\Url\Url;
use SprykerFeature\Client\SspAssetManagement\SspAssetManagementClientInterface;
use SprykerFeature\Yves\SspAssetManagement\Form\SspAssetForm;
use SprykerFeature\Yves\SspAssetManagement\Plugin\Router\SspAssetRouteProviderPlugin;
use SprykerFeature\Yves\SspAssetManagement\SspAssetManagementConfig;

class SspAssetFormDataProvider implements SspAssetFormDataProviderInterface
{
    /**
     * @param \SprykerFeature\Client\SspAssetManagement\SspAssetManagementClientInterface $sspAssetManagementClient
     * @param \SprykerFeature\Yves\SspAssetManagement\SspAssetManagementConfig $config
     */
    public function __construct(
        protected SspAssetManagementClientInterface $sspAssetManagementClient,
        protected SspAssetManagementConfig $config
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
                        ->setWithCompanyBusinessUnit(true)
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
        $sspAssetImagePath = Url::generate(SspAssetRouteProviderPlugin::ROUTE_NAME_ASSET_VIEW_IMAGE, ['ssp-asset-reference' => $sspAssetTransfer->getReferenceOrFail()])->build();

        return sprintf('%s/%s', $this->config->getYvesBaseUrl(), $sspAssetImagePath);
    }
}
