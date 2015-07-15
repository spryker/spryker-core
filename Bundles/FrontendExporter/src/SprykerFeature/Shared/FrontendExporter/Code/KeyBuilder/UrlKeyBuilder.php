<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder;

class UrlKeyBuilder implements KeyBuilderInterface
{

    use KeyBuilderTrait;

    /**
     * @param array $data
     *
     * @return string
     */
    protected function buildKey($data)
    {
        return $data;
    }

    /**
     * @return string
     */
    public function getBundleName()
    {
        return 'url';
    }

}
