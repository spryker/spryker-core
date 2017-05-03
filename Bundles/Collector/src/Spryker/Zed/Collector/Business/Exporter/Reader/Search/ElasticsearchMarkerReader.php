<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Reader\Search;

use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;

class ElasticsearchMarkerReader extends ElasticsearchReader implements ReaderInterface
{

    const READER_NAME = 'elastic-search-marker-reader';

    /**
     * @param string $key
     * @param string $type
     *
     * @return null|string
     */
    public function read($key, $type = '')
    {
        $mapping = $this->index->getType($this->type)->getMapping();

        if (isset($mapping[$this->type][self::META_ATTRIBUTE][$key])) {
            return $mapping[$this->type][self::META_ATTRIBUTE][$key];
        }

        return null;
    }

}
