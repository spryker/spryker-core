<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAttribute\Business\Model;

interface AttributeMapperInterface
{

    /**
     * @param array $attributes
     *
     * @return string
     */
    public function encodeJsonAttributes(array $attributes);

    /**
     * @param string $attributesJson
     *
     * @return array
     */
    public function decodeJsonAttributes($attributesJson);

    /**
     * @param array|\Orm\Zed\Product\Persistence\SpyProductAttributeKey[]|\Propel\Runtime\Collection\ObjectCollection $metaAttributeCollection
     *
     * @return array
     */
    public function mapMetaAttributes($metaAttributeCollection);

    /**
     * @param array|\Orm\Zed\Product\Persistence\SpyProductAttributeKey[]|\Propel\Runtime\Collection\ObjectCollection $metaAttributeCollection
     *
     * @return array
     */
    public function maSuggestKeys($metaAttributeCollection);

    /**
     * $productAttributes format
     * [
     *   [_] => [key=>value, key2=>value2]
     *   [46] => [key=>value]
     *   [66] => [key3=>value3, key5=value5]
     * ]
     *
     * @see ProductAttributeConfig::DEFAULT_LOCALE
     *
     * @param array $productAttributes
     *
     * @return array
     */
    public function extractKeysFromAttributes(array $productAttributes);

    /**
     * $productAttributes format
     * [
     *   [_] => [key=>value, key2=>value2]
     *   [46] => [key=>value]
     *   [66] => [key3=>value3, key5=value5]
     * ]
     *
     * @see ProductAttributeConfig::DEFAULT_LOCALE
     *
     * @param array $productAttributes
     *
     * @return array
     */
    public function extractValuesFromAttributes(array $productAttributes);

    /**
     * @param array $data
     *
     * @return array
     */
    public function hydrateAttributeItem(array $data);

}
