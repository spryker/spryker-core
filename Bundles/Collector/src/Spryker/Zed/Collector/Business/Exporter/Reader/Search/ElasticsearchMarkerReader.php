<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Reader\Search;

class ElasticsearchMarkerReader extends ElasticsearchReader
{
    public const READER_NAME = 'elastic-search-marker-reader';
    public const META_ATTRIBUTE = '_meta';

    /**
     * @param string $key
     * @param string $type
     *
     * @return string|null
     */
    public function read($key, $type = '')
    {
        $typeName = $this->getType()->getName();
        $mapping = $this->getType()->getMapping();

        if (isset($mapping[$typeName][static::META_ATTRIBUTE][$key])) {
            return $mapping[$typeName][static::META_ATTRIBUTE][$key];
        }

        return null;
    }
}
