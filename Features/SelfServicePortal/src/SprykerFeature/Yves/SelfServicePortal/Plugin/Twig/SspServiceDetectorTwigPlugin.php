<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SelfServicePortal\Plugin\Twig;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalFactory getFactory()
 * @method \SprykerFeature\Yves\SelfServicePortal\SelfServicePortalConfig getConfig()
 */
class SspServiceDetectorTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    /**
     * @var string
     */
    protected const FUNCTION_NAME_IS_SSP_SERVICE = 'isSspService';

    /**
     * @var string
     */
    protected const PARAMETER_PRODUCT_ABSTRACT_TYPES = 'product-abstract-types';

    /**
     * {@inheritDoc}
     * - Extends Twig with `isSspService()` function.
     *
     * @api
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $twig->addFunction($this->getIsSspServiceFunction());

        return $twig;
    }

    /**
     * @return \Twig\TwigFunction
     */
    protected function getIsSspServiceFunction(): TwigFunction
    {
        return new TwigFunction(static::FUNCTION_NAME_IS_SSP_SERVICE, function (array|ProductViewTransfer $productData): bool {
            return $this->isSspService($productData);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer|array<string|mixed> $productData
     *
     * @return bool
     */
    protected function isSspService(array|ProductViewTransfer $productData): bool
    {
        $productServiceTypeName = $this->getConfig()->getServiceProductTypeName();

        $productTypes = $this->getProductTypes($productData);

        if (!$productTypes) {
            return false;
        }

        return in_array($productServiceTypeName, $productTypes, true);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer|array<string|mixed> $productData
     *
     * @return array<string>
     */
    protected function getProductTypes(array|ProductViewTransfer $productData): array
    {
        if (is_array($productData)) {
            return $productData[static::PARAMETER_PRODUCT_ABSTRACT_TYPES] ?? [];
        }

        /**
         * @var \Generated\Shared\Transfer\ProductViewTransfer $productData
         */
        return $productData->getProductTypes();
    }
}
