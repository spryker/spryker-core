<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 */
class SspAssetFilterNameWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_SSP_ASSET_REFERENCE = 'ssp-asset-reference';

    /**
     * @var string
     */
    protected const PARAMETER_ITEMS_FOUND = 'itemsFound';

    /**
     * @var string
     */
    protected const PARAMETER_SSP_ASSET_NAME = 'sspAssetName';

    /**
     * @var string
     */
    protected const PARAMETER_IS_DISABLED = 'isDisabled';

    public function __construct(int $itemsFound = 0)
    {
        $this->addItemsFoundParameter($itemsFound);

        $companyUserTransfer = $this->getFactory()->getCompanyUserClient()->findCompanyUser();

        if (!$companyUserTransfer) {
            return;
        }

        $this->addSspAssetNameParameter($companyUserTransfer);
        $this->addIsDisabledParameter();
    }

    public static function getName(): string
    {
        return 'SspAssetFilterNameWidget';
    }

    public static function getTemplate(): string
    {
        return '@SelfServicePortal/views/asset-filter-name/asset-filter-name.twig';
    }

    protected function addItemsFoundParameter(int $itemsFound): void
    {
        $this->addParameter(static::PARAMETER_ITEMS_FOUND, $itemsFound);
    }

    protected function addSspAssetNameParameter(CompanyUserTransfer $companyUserTransfer): void
    {
        $sspAssetName = $this->getSspAssetName($companyUserTransfer);
        $this->addParameter(static::PARAMETER_SSP_ASSET_NAME, $sspAssetName);
    }

    protected function addIsDisabledParameter(): void
    {
        $this->addParameter(static::PARAMETER_IS_DISABLED, false);
    }

    protected function getSspAssetName(CompanyUserTransfer $companyUserTransfer): ?string
    {
        $request = $this->getFactory()->getRequestStack()->getCurrentRequest();

        if (!$request) {
            return null;
        }

        /** @var string|null $assetReference */
        $assetReference = $request->query->get(static::PARAMETER_SSP_ASSET_REFERENCE);

        if (!$assetReference) {
            return null;
        }

        $sspAssetStorageTransfer = $this->getFactory()
            ->createSspAssetStorageReader()
            ->findSspAssetStorageByReference($companyUserTransfer, $assetReference);

        return $sspAssetStorageTransfer?->getName();
    }
}
