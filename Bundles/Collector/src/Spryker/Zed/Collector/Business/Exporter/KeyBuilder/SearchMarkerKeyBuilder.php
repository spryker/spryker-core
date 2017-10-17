<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\KeyBuilder;

use Spryker\Shared\KeyBuilder\KeyBuilderInterface;
use Spryker\Shared\KeyBuilder\KeyBuilderTrait;

class SearchMarkerKeyBuilder implements KeyBuilderInterface
{
    use KeyBuilderTrait;

    /**
     * @param string $data
     *
     * @return string
     */
    protected function buildKey($data)
    {
        return $data . $this->keySeparator . 'timestamp';
    }

    /**
     * @return string
     */
    public function getBundleName()
    {
        return 'search-export';
    }
}
