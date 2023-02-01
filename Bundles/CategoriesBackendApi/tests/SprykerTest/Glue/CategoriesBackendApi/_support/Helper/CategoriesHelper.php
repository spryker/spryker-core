<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\CategoriesBackendApi\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\ApiCategoryAttributesBuilder;
use Generated\Shared\DataBuilder\ApiCategoryLocalizedAttributeBuilder;
use Generated\Shared\DataBuilder\ApiCategoryParentBuilder;
use Generated\Shared\Transfer\ApiCategoryAttributesTransfer;
use Generated\Shared\Transfer\ApiCategoryLocalizedAttributeTransfer;
use Generated\Shared\Transfer\ApiCategoryParentTransfer;
use Spryker\Shared\Config\Config;
use Spryker\Shared\GlueBackendApiApplication\GlueBackendApiApplicationConstants;
use Spryker\Shared\ZedRequest\ZedRequestConstants;

class CategoriesHelper extends Module
{
    /**
     * @param string|null $categoryIdentifier
     *
     * @return string
     */
    public function buildCategoriesUrl(?string $categoryIdentifier = null): string
    {
        return $categoryIdentifier !== null ? $this->buildBackendApiUrl('categories/{id}', ['id' => $categoryIdentifier]) : $this->buildBackendApiUrl('categories');
    }

    /**
     * @param string $url
     * @param array<mixed> $params
     *
     * @return string
     */
    protected function buildBackendApiUrl(string $url, array $params = []): string
    {
        $url = sprintf('%s://%s/%s', Config::get(ZedRequestConstants::ZED_API_SSL_ENABLED) ? 'https' : 'http', Config::get(GlueBackendApiApplicationConstants::GLUE_BACKEND_API_HOST), $this->formatUrl($url, $params));

        return rtrim($url, '/');
    }

    /**
     * @param string $url
     * @param array<mixed> $params
     *
     * @return string
     */
    protected function formatUrl(string $url, array $params): string
    {
        $refinedParams = [];
        foreach ($params as $key => $value) {
            $refinedParams['{' . $key . '}'] = urlencode($value);
        }

        return strtr($url, $refinedParams);
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ApiCategoryAttributesTransfer
     */
    public function haveApiCategoryAttributesTransfer(array $seed = []): ApiCategoryAttributesTransfer
    {
        return (new ApiCategoryAttributesBuilder($seed))->build();
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ApiCategoryParentTransfer
     */
    public function haveApiCategoryParentTransfer(array $seed = []): ApiCategoryParentTransfer
    {
        return (new ApiCategoryParentBuilder($seed))->build();
    }

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\ApiCategoryLocalizedAttributeTransfer
     */
    public function haveApiCategoryLocalizedAttributeTransfer(array $seed = []): ApiCategoryLocalizedAttributeTransfer
    {
        return (new ApiCategoryLocalizedAttributeBuilder($seed))->build();
    }
}
