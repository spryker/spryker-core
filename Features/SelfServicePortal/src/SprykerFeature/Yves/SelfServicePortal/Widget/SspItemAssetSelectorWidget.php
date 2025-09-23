<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 */
class SspItemAssetSelectorWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const NAME = 'SspItemAssetSelectorWidget';

    /**
     * @var string
     */
    protected const PARAMETER_SSP_ASSET_REFERENCE = 'ssp-asset-reference';

    /**
     * @var string
     */
    protected const PARAMETER_IS_DISABLED = 'isDisabled';

    /**
     * @var string
     */
    protected const PARAMETER_IS_VISIBLE = 'isVisible';

    /**
     * @var string
     */
    protected const PARAMETER_PRODUCT = 'product';

    /**
     * @var string
     */
    protected const PARAMETER_ASSET = 'asset';

    public function __construct(
        ProductViewTransfer $productViewTransfer,
        bool $isDisabled = false
    ) {
        $this->addIsDisabledParameter($isDisabled);
        $this->addProductParameter($productViewTransfer);

        $companyUserTransfer = $this->getFactory()->getCompanyUserClient()->findCompanyUser();
        if (!$companyUserTransfer) {
            $this->addIsVisibleParameter(false);

            return;
        }

        $this->addIsVisibleParameter(true);
        $this->addAssetParameter($companyUserTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@SelfServicePortal/views/item-asset-selector/item-asset-selector.twig';
    }

    protected function addProductParameter(ProductViewTransfer $productViewTransfer): void
    {
        $this->addParameter(static::PARAMETER_PRODUCT, $productViewTransfer);
    }

    protected function addIsDisabledParameter(bool $isDisabled): void
    {
        $this->addParameter(static::PARAMETER_IS_DISABLED, $isDisabled);
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
            $this->addParameter(static::PARAMETER_ASSET, null);

            return;
        }

        $sspAssetStorageTransfer = $this->getFactory()->createSspAssetStorageReader()->findSspAssetStorageByReference($companyUserTransfer, $assetReference);

        if ($sspAssetStorageTransfer) {
            $sspAssetStorageTransfer->setReference($assetReference);
        }

        $this->addParameter(static::PARAMETER_ASSET, $sspAssetStorageTransfer);
    }

    protected function addIsVisibleParameter(bool $isVisible): void
    {
        $this->addParameter(static::PARAMETER_IS_VISIBLE, $isVisible);
    }
}
