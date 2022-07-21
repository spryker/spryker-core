<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Url\KeyBuilder;

use Spryker\Shared\KeyBuilder\KeyBuilderTrait;
use Spryker\Shared\Url\UrlConfig;

class UrlKeyBuilder
{
    use KeyBuilderTrait;

    /**
     * @param array<string, mixed> $data
     *
     * @return array
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

    /**
     * @return string
     */
    public function getResourceType()
    {
        return UrlConfig::RESOURCE_TYPE_URL;
    }
}
