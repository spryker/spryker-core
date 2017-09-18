<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Reader;

interface ReaderInterface
{

    /**
     * @param string $key
     * @param string $type
     * @param string $typeName
     * @param string $indexName
     *
     * @return mixed
     */
    public function read($key, $type = '', $typeName = '', $indexName = '');

    /**
     * @return string
     */
    public function getName();

}
