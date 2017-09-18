<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Search\Model\Elasticsearch\Writer;

interface WriterInterface
{

    /**
     * @param array $dataSet
     * @param string $typeName
     * @param string $indexName
     *
     * @return bool
     */
    public function write(array $dataSet, $typeName = '', $indexName = '');

    /**
     * @param array $dataSet
     * @param string $typeName
     * @param string $indexName
     *
     * @return bool
     */
    public function delete(array $dataSet, $typeName = '', $indexName = '');

    /**
     * @return string
     */
    public function getName();

}
