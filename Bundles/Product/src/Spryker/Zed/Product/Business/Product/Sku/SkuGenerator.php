<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Product\Business\Product\Sku;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Dependency\Service\ProductToUtilTextInterface;

class SkuGenerator implements SkuGeneratorInterface
{
    /**
     * @var string
     */
    public const SKU_ABSTRACT_SEPARATOR = '-';

    /**
     * @var string
     */
    public const SKU_TYPE_SEPARATOR = '-';

    /**
     * @var string
     */
    public const SKU_VALUE_SEPARATOR = '_';

    /**
     * @var int
     */
    public const SKU_MAX_LENGTH = 255;

    /**
     * @var \Spryker\Zed\Product\Dependency\Service\ProductToUtilTextInterface
     */
    protected $utilTextService;

    /**
     * @var \Spryker\Zed\Product\Business\Product\Sku\SkuIncrementGeneratorInterface
     */
    protected $skuIncrementGenerator;

    /**
     * @param \Spryker\Zed\Product\Dependency\Service\ProductToUtilTextInterface $utilTextService
     * @param \Spryker\Zed\Product\Business\Product\Sku\SkuIncrementGeneratorInterface $skuIncrementGenerator
     */
    public function __construct(ProductToUtilTextInterface $utilTextService, SkuIncrementGeneratorInterface $skuIncrementGenerator)
    {
        $this->utilTextService = $utilTextService;
        $this->skuIncrementGenerator = $skuIncrementGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return string
     */
    public function generateProductAbstractSku(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->sanitizeSku($productAbstractTransfer->getSku());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string
     */
    public function generateProductConcreteSku(
        ProductAbstractTransfer $productAbstractTransfer,
        ProductConcreteTransfer $productConcreteTransfer
    ) {
        $concreteSku = $this->generateConcreteSkuFromAttributes($productConcreteTransfer->getAttributes());

        if ($concreteSku === '') {
            $concreteSku = $this->addSkuIncrementValue($productAbstractTransfer->getIdProductAbstract());
        }

        $concreteSku = $this->formatConcreteSku($productAbstractTransfer->getSku(), $concreteSku);

        return $concreteSku;
    }

    /**
     *  - Transliterates from UTF-8 to ASCII character set
     *  - Removes all non Alphanumeric and (.,-,_) characters
     *  - Replaces all space characters with dashes
     *  - Replaces multiple dashes with single dash
     *
     * @param string $sku
     *
     * @return string
     */
    protected function sanitizeSku($sku)
    {
        if (function_exists('iconv')) {
            /** @var string $sku */
            $sku = iconv('UTF-8', 'ASCII//TRANSLIT', $sku);
        }

        $sku = preg_replace("/[^a-zA-Z0-9\.\-\_]/", '', trim((string)$sku));
        $sku = preg_replace('/\s+/', '-', $sku);
        $sku = preg_replace('/(\-)\1+/', '$1', $sku);

        return $sku;
    }

    /**
     * @param string $abstractSku
     * @param string $concreteSku
     *
     * @return string
     */
    protected function formatConcreteSku($abstractSku, $concreteSku)
    {
        $formattedSku = $this->sanitizeSku(sprintf(
            '%s%s%s',
            $abstractSku,
            static::SKU_ABSTRACT_SEPARATOR,
            $concreteSku,
        ));
        $formattedSku = substr($formattedSku, 0, static::SKU_MAX_LENGTH);
        $formattedSku = rtrim($formattedSku, implode('', [
            static::SKU_TYPE_SEPARATOR,
            static::SKU_VALUE_SEPARATOR,
        ]));

        return $formattedSku;
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    protected function generateConcreteSkuFromAttributes(array $attributes)
    {
        $sku = '';
        foreach ($attributes as $type => $value) {
            $sku .= $type . static::SKU_TYPE_SEPARATOR . $value . static::SKU_VALUE_SEPARATOR;
        }

        return rtrim($sku, static::SKU_VALUE_SEPARATOR);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return string
     */
    protected function addSkuIncrementValue(int $idProductAbstract): string
    {
        return $this->skuIncrementGenerator->generateProductConcreteSkuIncrement($idProductAbstract);
    }
}
