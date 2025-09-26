<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 */
class SspCartItemAssetSelectorWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const NAME = 'SspCartItemAssetSelectorWidget';

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
    protected const PARAMETER_PRODUCT_ITEM = 'productItem';

    /**
     * @var string
     */
    protected const PARAMETER_ASSET = 'asset';

    /**
     * @var string
     */
    protected const PARAMETER_FORM = 'form';

    public function __construct(ItemTransfer $productViewTransfer)
    {
        $this->addProductItemParameter($productViewTransfer);

        $this->addFormParameter($productViewTransfer);

        $companyUserTransfer = $this->getFactory()->getCompanyUserClient()->findCompanyUser();
        if (!$companyUserTransfer) {
            $this->addIsVisibleParameter(false);

            return;
        }

        $this->addIsVisibleParameter(true);
        $this->addSspAssetParameter($companyUserTransfer, $productViewTransfer);
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
        return '@SelfServicePortal/views/cart-item-asset-selector/cart-item-asset-selector.twig';
    }

    protected function addProductItemParameter(ItemTransfer $productViewTransfer): void
    {
        $this->addParameter(static::PARAMETER_PRODUCT_ITEM, $productViewTransfer);
    }

    protected function addIsDisabledParameter(bool $isDisabled): void
    {
        $this->addParameter(static::PARAMETER_IS_DISABLED, $isDisabled);
    }

    protected function addSspAssetParameter(CompanyUserTransfer $companyUserTransfer, ItemTransfer $itemTransfer): void
    {
        if (!$itemTransfer->getSspAsset()) {
            $this->addParameter(static::PARAMETER_ASSET, null);

            return;
        }

        $sspAssetStorageTransfer = $this->getFactory()->createSspAssetStorageReader()->findSspAssetStorageByReference(
            $companyUserTransfer,
            $itemTransfer->getSspAsset()->getReferenceOrFail(),
        );

        if ($sspAssetStorageTransfer) {
            $sspAssetStorageTransfer->setReference($itemTransfer->getSspAsset()->getReference());
        }

        $this->addParameter(static::PARAMETER_ASSET, $sspAssetStorageTransfer);
    }

    protected function addIsVisibleParameter(bool $isVisible): void
    {
        $this->addParameter(static::PARAMETER_IS_VISIBLE, $isVisible);
    }

    protected function addFormParameter(ItemTransfer $itemTransfer): void
    {
        $formData = [
            'sku' => $itemTransfer->getSku(),
            'groupKey' => $itemTransfer->getGroupKey(),
            'sspAsset' => $itemTransfer->getSspAsset()?->getReference(),
        ];

        $form = $this->getFactory()->createQuoteItemSspAssetForm($formData);
        $this->addParameter(static::PARAMETER_FORM, $form->createView());
    }
}
