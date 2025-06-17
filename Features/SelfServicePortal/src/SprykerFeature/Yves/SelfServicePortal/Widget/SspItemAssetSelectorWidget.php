<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

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
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param bool $isDisabled
     */
    public function __construct(
        ProductViewTransfer $productViewTransfer,
        bool $isDisabled = false
    ) {
        $this->addProductParameter($productViewTransfer);
        $this->addIsDisabledParameter($isDisabled);
        $this->addIsVisibleParameter();
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

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    protected function addProductParameter(ProductViewTransfer $productViewTransfer): void
    {
        $this->addParameter(static::PARAMETER_PRODUCT, $productViewTransfer);
    }

    /**
     * @param bool $isDisabled
     *
     * @return void
     */
    protected function addIsDisabledParameter(bool $isDisabled): void
    {
        $this->addParameter(static::PARAMETER_IS_DISABLED, $isDisabled);
    }

    /**
     * @return void
     */
    protected function addIsVisibleParameter(): void
    {
        $isVisible = $this->getFactory()->getCustomerClient()->isLoggedIn();

        $this->addParameter(static::PARAMETER_IS_VISIBLE, $isVisible);
    }
}
