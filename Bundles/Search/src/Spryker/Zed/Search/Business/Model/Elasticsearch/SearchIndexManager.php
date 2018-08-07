<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch;

use Elastica\Exception\ResponseException;
use Elastica\Index;

class SearchIndexManager implements SearchIndexManagerInterface
{
    /**
     * @var \Elastica\Index
     */
    private $index;

    /**
     * @param \Elastica\Index $index
     */
    public function __construct(Index $index)
    {
        $this->index = $index;
    }

    /**
     * @return int
     */
    public function getTotalCount()
    {
        try {
            return $this->index->count();
        } catch (ResponseException $e) {
            return 0;
        }
    }

    /**
     * @return array
     */
    public function getMetaData()
    {
        $metaData = [];

        try {
            $mapping = $this->index->getMapping();

            if (isset($mapping['page']) && isset($mapping['page']['_meta'])) {
                $metaData = $mapping['page']['_meta'];
            }
        } catch (ResponseException $e) {
            // legal catch, if no mapping found (fresh installation etc) we still want to show empty meta data
        }

        return $metaData;
    }

    /**
     * @return \Elastica\Response
     */
    public function delete()
    {
        return $this->index->delete();
    }

    /**
     * @param string $key
     * @param string $type
     *
     * @return \Elastica\Document
     */
    public function getDocument($key, $type)
    {
        return $this->index->getType($type)->getDocument($key);
    }

    /**
     * @return bool
     */
    public function close()
    {
        return $this->index->close()->isOk();
    }

    /**
     * @return bool
     */
    public function open(): bool
    {
        return $this->index->open()->isOk();
    }
}
