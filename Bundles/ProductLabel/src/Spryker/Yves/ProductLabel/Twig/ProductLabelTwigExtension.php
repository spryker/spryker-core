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
    public const FUNCTION_PRODUCT_ABSTRACT_LABELS = 'spyProductAbstractLabels';
    public const FUNCTION_PRODUCT_LABELS = 'spyProductLabels';

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
            $this->createProductAbstractLabelsFunction(),
            $this->createProductLabelsFunction(),
        ];
    }

    /**
     * @return \Twig_SimpleFunction
     */
    protected function createProductAbstractLabelsFunction()
    {
        return new Twig_SimpleFunction(
            static::FUNCTION_PRODUCT_ABSTRACT_LABELS,
            [$this, 'renderProductAbstractLabels'],
            [
                'is_safe' => ['html'],
                'needs_environment' => true,
            ]
        );
    }

    /**
     * @return \Twig_SimpleFunction
     */
    protected function createProductLabelsFunction()
    {
        return new Twig_SimpleFunction(
            static::FUNCTION_PRODUCT_LABELS,
            [$this, 'renderProductLabels'],
            [
                'is_safe' => ['html'],
                'needs_environment' => true,
            ]
        );
    }

    /**
     * @param \Twig_Environment $twig
     * @param int $idProductAbstract
     * @param string $templateName
     *
     * @return string
     */
    public function renderProductAbstractLabels(Twig_Environment $twig, $idProductAbstract, $templateName)
    {
        $productLabelTransferCollection = $this
            ->productLabelClient
            ->findLabelsByIdProductAbstract($idProductAbstract, $this->localeName);

        if (!$productLabelTransferCollection) {
            return '';
        }

        return $twig->render($templateName, [
            'productLabelTransferCollection' => $productLabelTransferCollection,
        ]);
    }

    /**
     * @param \Twig_Environment $twig
     * @param array $idProductLabels
     * @param string $templateName
     *
     * @return string
     */
    public function renderProductLabels(Twig_Environment $twig, array $idProductLabels, $templateName)
    {
        $productLabelTransferCollection = $this
            ->productLabelClient
            ->findLabels($idProductLabels, $this->localeName);

        if (!$productLabelTransferCollection) {
            return '';
        }

        return $twig->render($templateName, [
            'productLabelTransferCollection' => $productLabelTransferCollection,
        ]);
    }
}
