<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use LogicException;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class SspServiceDetectorWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_IS_SERVICE = 'isService';

    /**
     * @var string
     */
    protected const PARAMETER_PRODUCT_CLASS_NAMES = 'product-class-names';

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer|array<string|mixed> $productData
     */
    public function __construct(array|ProductViewTransfer $productData)
    {
        $this->addIsServiceParameter($productData);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer|array<string|mixed> $productData
     *
     * @return void
     */
    protected function addIsServiceParameter(array|ProductViewTransfer $productData): void
    {
        $this->addParameter(static::PARAMETER_IS_SERVICE, $this->isSspService($productData));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'SspServiceDetectorWidget';
    }

    /**
     * @throws \LogicException
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        throw new LogicException('This widget should only be used as service detector.');
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer|array<string|mixed> $productData
     *
     * @return bool
     */
    protected function isSspService(array|ProductViewTransfer $productData): bool
    {
        $serviceProductClassName = $this->getConfig()->getServiceProductClassName();

        $productClassNames = $this->getProductClassNames($productData);

        if (!$productClassNames) {
            return false;
        }

        return in_array($serviceProductClassName, $productClassNames, true);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer|array<string|mixed> $productData
     *
     * @return array<string>
     */
    protected function getProductClassNames(array|ProductViewTransfer $productData): array
    {
        if (is_array($productData)) {
            return $productData[static::PARAMETER_PRODUCT_CLASS_NAMES] ?? [];
        }

        /**
         * @var \Generated\Shared\Transfer\ProductViewTransfer $productData
         */
        return $productData->getProductClassNames();
    }
}
