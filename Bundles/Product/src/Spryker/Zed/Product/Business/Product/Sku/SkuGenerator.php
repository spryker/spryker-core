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
    protected const SKU_ABSTRACT_SEPARATOR = '-';
    protected const SKU_TYPE_SEPARATOR = '-';
    protected const SKU_VALUE_SEPARATOR = '_';
    public const SKU_MAX_LENGTH = 255;

    /**
     * @var \Spryker\Zed\Product\Dependency\Service\ProductToUtilTextInterface
     */
    protected $utilTextService;

    /**
     * @param \Spryker\Zed\Product\Dependency\Service\ProductToUtilTextInterface $utilTextService
     */
    public function __construct(ProductToUtilTextInterface $utilTextService)
    {
        $this->utilTextService = $utilTextService;
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
            $sku = iconv('UTF-8', 'ASCII//TRANSLIT', $sku);
        }

        $sku = preg_replace("/[^a-zA-Z0-9\.\-\_]/", "", trim($sku));
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
            $concreteSku
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
}
