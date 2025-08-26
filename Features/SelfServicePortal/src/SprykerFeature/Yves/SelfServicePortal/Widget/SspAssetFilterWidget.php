<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 */
class SspAssetFilterWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_SSP_ASSET_REFERENCE = 'ssp-asset-reference';

    public function __construct()
    {
        $companyUserTransfer = $this->getFactory()->getCompanyUserClient()->findCompanyUser();
        if (!$companyUserTransfer) {
            return;
        }

        $this->addSspAssetDataParameter($companyUserTransfer);
        $this->addIsDisabledParameter();
    }

    public static function getName(): string
    {
        return 'SspAssetFilterWidget';
    }

    public static function getTemplate(): string
    {
        return '@SelfServicePortal/views/asset-filter/asset-filter.twig';
    }

    protected function addSspAssetDataParameter(CompanyUserTransfer $companyUserTransfer): void
    {
        $request = $this->getFactory()->getRequestStack()->getCurrentRequest();

        if (!$request) {
            $this->addParameter('sspAssetData', null);

            return;
        }

        /** @var string|null $assetReference */
        $assetReference = $request->query->get(static::PARAMETER_SSP_ASSET_REFERENCE);

        if (!$assetReference) {
            $this->addParameter('sspAssetData', null);

            return;
        }

        $sspAssetData = $this->getFactory()
            ->createSspAssetStorageReader()
            ->getSspAssetDataByReference($companyUserTransfer, $assetReference);

        $this->addParameter('sspAssetData', $sspAssetData);
    }

    protected function addIsDisabledParameter(): void
    {
        $this->addParameter('isDisabled', false);
    }
}
