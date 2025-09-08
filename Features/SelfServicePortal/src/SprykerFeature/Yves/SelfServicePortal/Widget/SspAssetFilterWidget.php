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

    /**
     * @var string
     */
    protected const PARAMETER_ASSET = 'asset';

    public function __construct()
    {
        $companyUserTransfer = $this->getFactory()->getCompanyUserClient()->findCompanyUser();
        if (!$companyUserTransfer) {
            $this->addIsDisabledParameter(true);

            return;
        }

        $this->addAssetParameter($companyUserTransfer);
        $this->addIsDisabledParameter(false);
    }

    public static function getName(): string
    {
        return 'SspAssetFilterWidget';
    }

    public static function getTemplate(): string
    {
        return '@SelfServicePortal/views/asset-filter/asset-filter.twig';
    }

    protected function addAssetParameter(CompanyUserTransfer $companyUserTransfer): void
    {
        $request = $this->getFactory()->getRequestStack()->getCurrentRequest();

        if (!$request) {
            return;
        }

        /** @var string|null $assetReference */
        $assetReference = $request->query->get(static::PARAMETER_SSP_ASSET_REFERENCE);

        if (!$assetReference) {
            return;
        }

        $sspAssetStorageTransfer = $this->getFactory()->createSspAssetStorageReader()->findSspAssetStorageByReference($companyUserTransfer, $assetReference);

        if (!$sspAssetStorageTransfer) {
            $this->addParameter(static::PARAMETER_ASSET, null);

            return;
        }

        $sspAssetStorageTransfer->setReference($assetReference);

        $this->addParameter(static::PARAMETER_ASSET, $sspAssetStorageTransfer);
    }

    protected function addIsDisabledParameter(bool $isDisabled): void
    {
        $this->addParameter('isDisabled', $isDisabled);
    }
}
