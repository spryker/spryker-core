<?php

namespace SprykerFeature\Shared\ProductFrontendExporterConnector\Code\KeyBuilder;

use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\AbstractKeyBuilder;

/**
 * Class ProductUrlKeyBuilder
 *
 * @package SprykerFeature\Zed\ProductFrontendExporterConnector\Business\Builder
 */
class ProductUrlKeyBuilder extends AbstractKeyBuilder
{
    /**
     * @param mixed     $data
     * @param string    $locale
     *
     * @return string
     */
    public function generateKey($data, $locale)
    {
        $key = implode($this->keySeparator, [$locale, 'product', 'url', $data]);

        return $this->escapeKey($key);
    }

    /**
     * @param array $data
     *
     * @return string
     */
    protected function buildKey($data)
    {
        return 'url.' . $data;
    }

    /**
     * @return string
     */
    public function getBundleName()
    {
        return 'product';
    }
}
