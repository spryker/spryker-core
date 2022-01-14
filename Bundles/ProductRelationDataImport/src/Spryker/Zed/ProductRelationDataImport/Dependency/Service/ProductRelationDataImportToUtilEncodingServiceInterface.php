<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductRelationDataImport\Dependency\Service;

interface ProductRelationDataImportToUtilEncodingServiceInterface
{
    /**
     * @param string $jsonValue
     * @param bool $assoc Deprecated: `false` is deprecated, always use `true` for array return.
     * @param int|null $depth
     * @param int|null $options
     *
     * @return array<mixed>|null
     */
    public function decodeJson($jsonValue, $assoc = false, $depth = null, $options = null);
}
