<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\SearchPage\Code\KeyBuilder;

use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderTrait;

class SharedSearchPageConfigKeyBuilder implements KeyBuilderInterface
{

    use KeyBuilderTrait;

    /**
     * @param mixed $data
     *
     * @return string
     */
    protected function buildKey($data)
    {
        return 'search.page.config';
    }

    /**
     * @return string
     */
    public function getBundleName()
    {
        return 'search-page';
    }

}
