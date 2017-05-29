<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\ProductLabel\Twig;

use Spryker\Client\ProductLabel\ProductLabelClientInterface;
use Spryker\Shared\Twig\TwigExtension;
use Twig_Environment;
use Twig_SimpleFunction;

class ProductLabelTwigExtension extends TwigExtension
{

    const FUNCTION_NAME = 'spyProductLabelItems';

    /**
     * @var \Spryker\Client\ProductLabel\ProductLabelClientInterface
     */
    protected $productLabelClient;

    /**
     * @var string
     */
    protected $localeName;

    /**
     * @param \Spryker\Client\ProductLabel\ProductLabelClientInterface $productLabelClient
     * @param string $localeName
     */
    public function __construct(ProductLabelClientInterface $productLabelClient, $localeName)
    {
        $this->productLabelClient = $productLabelClient;
        $this->localeName = $localeName;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction(
                static::FUNCTION_NAME,
                [$this, 'renderProductLabelItems'],
                [
                    'is_safe' => ['html'],
                    'needs_environment' => true,
                ]
            ),
        ];
    }

    /**
     * @param \Twig_Environment $twig
     * @param int $idProductAbstract
     * @param string $templateName
     *
     * @return string
     */
    public function renderProductLabelItems(Twig_Environment $twig, $idProductAbstract, $templateName)
    {
        $productLabelTransferCollection = $this
            ->productLabelClient
            ->getLabelsForAbstractProduct($idProductAbstract, $this->localeName);

        if (!count($productLabelTransferCollection)) {
            return '';
        }

        return $twig->render(
            $templateName,
            [
                'productLabelTransferCollection' => $productLabelTransferCollection,
            ]
        );
    }

}
