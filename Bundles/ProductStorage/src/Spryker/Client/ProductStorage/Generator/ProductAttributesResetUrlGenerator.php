<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductStorage\Generator;

use Generated\Shared\Transfer\ProductViewTransfer;
use Symfony\Component\HttpFoundation\Request;

class ProductAttributesResetUrlGenerator implements ProductAttributesResetUrlGeneratorInterface
{
    /**
     * @var string
     */
    protected const REQUEST_PARAM_ATTRIBUTES = 'attributes';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param list<\Generated\Shared\Transfer\ProductViewTransfer> $productViewTransfers
     *
     * @return array<int, array<string, string>>
     */
    public function generateProductAttributesResetUrlQueryParameters(Request $request, array $productViewTransfers): array
    {
        $requestQueryParameters = $request->query->all();

        $optionResetUrlQueryParameters = [];
        foreach ($productViewTransfers as $productViewTransfer) {
            $optionResetUrlQueryParameters[$productViewTransfer->getIdProductAbstractOrFail()] = $this->getProductAttributesResetUrlQueryParameters($productViewTransfer, $requestQueryParameters);
        }

        return $optionResetUrlQueryParameters;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param array<string, mixed> $queryParameters
     *
     * @return array<string, string>
     */
    protected function getProductAttributesResetUrlQueryParameters(
        ProductViewTransfer $productViewTransfer,
        array $queryParameters
    ): array {
        $superAttributes = $productViewTransfer
            ->getAttributeMapOrFail()
            ->getSuperAttributes();

        $productAttributesResetUrlQueryParameters = [];
        foreach (array_keys($superAttributes) as $attributeName) {
            $productAttributesResetUrlQueryParameters[$attributeName] = $this->getProductAttributeResetUrlQueryParameters(
                $queryParameters,
                $productViewTransfer->getIdProductAbstractOrFail(),
                $attributeName,
            );
        }

        return $productAttributesResetUrlQueryParameters;
    }

    /**
     * @param array<string, mixed> $queryParameters
     * @param int $idProductAbstract
     * @param string $attributeName
     *
     * @return string
     */
    protected function getProductAttributeResetUrlQueryParameters(array $queryParameters, int $idProductAbstract, string $attributeName): string
    {
        unset($queryParameters[static::REQUEST_PARAM_ATTRIBUTES][$idProductAbstract][$attributeName]);

        return http_build_query($queryParameters);
    }
}
