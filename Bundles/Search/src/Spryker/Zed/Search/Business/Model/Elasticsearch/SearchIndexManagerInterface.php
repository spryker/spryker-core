<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Search\Business\Model\Elasticsearch;

interface SearchIndexManagerInterface
{
    /**
     * @return int
     */
    public function getTotalCount();

    /**
     * @return array
     */
    public function getMetaData();

    /**
     * @return \Elastica\Response
     */
    public function delete();

    /**
     * @param string $key
     * @param string $type
     *
     * @return \Elastica\Document
     */
    public function getDocument($key, $type);

    /**
     * @return bool
     */
    public function close();

    /**
     * @return bool
     */
    public function open(): bool;
}
