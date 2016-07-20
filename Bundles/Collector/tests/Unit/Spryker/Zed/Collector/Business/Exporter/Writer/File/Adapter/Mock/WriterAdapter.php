<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Collector\Business\Exporter\Writer\File\Adapter\Mock;

use Spryker\Zed\Collector\Business\Exporter\Writer\File\Adapter\AbstractAdapter;

class WriterAdapter extends AbstractAdapter
{

    /**
     * Make this method visible for testing
     *
     * @throws \Spryker\Zed\Collector\Business\Exporter\Exception\FileWriterException
     *
     * @return string
     */
    public function getAbsolutePath()
    {
        return parent::getAbsolutePath();
    }

    /**
     * @param array $dataSet
     * @param string $type
     *
     * @return int
     */
    public function write(array $dataSet, $type = '')
    {
        return 1;
    }

}
