<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ProductGroup\Twig;

use Spryker\Client\ProductGroup\ProductGroupClientInterface;
use Spryker\Shared\Twig\TwigExtension;
use Spryker\Yves\Kernel\Application;
use Twig_Environment;
use Twig_SimpleFunction;

class ProductGroupTwigExtension extends TwigExtension
{
    public const FUNCTION_NAME_PRODUCT_GROUP_ITEMS = 'spyProductGroupItems';

    /**
     * @var \Spryker\Client\ProductGroup\ProductGroupClientInterface
     */
    protected $productGroupClient;

    /**
     * @var \Spryker\Yves\Kernel\Application
     */
    protected $application;

    /**
     * @param \Spryker\Client\ProductGroup\ProductGroupClientInterface $productGroupClient
     * @param \Spryker\Yves\Kernel\Application $application
     */
    public function __construct(ProductGroupClientInterface $productGroupClient, Application $application)
    {
        $this->productGroupClient = $productGroupClient;
        $this->application = $application;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction(static::FUNCTION_NAME_PRODUCT_GROUP_ITEMS, [$this, 'renderProductGroupItems'], [
                'is_safe' => ['html'],
                'needs_environment' => true,
            ]),
        ];
    }

    /**
     * @param \Twig_Environment $twig
     * @param int $idProductAbstract
     * @param string $template
     *
     * @return string
     */
    public function renderProductGroupItems(Twig_Environment $twig, $idProductAbstract, $template)
    {
        $productGroupItems = $this->productGroupClient->findProductGroupItemsByIdProductAbstract($idProductAbstract, $this->getLocale());

        if (!$productGroupItems) {
            return '';
        }

        return $twig->render($template, [
            'productGroupItems' => $productGroupItems,
        ]);
    }

    /**
     * @return string
     */
    protected function getLocale()
    {
        return $this->application['locale'];
    }
}
