<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter\Writer;

interface WriterInterface
{
    /**
     * @param array<string, mixed> $dataSet
     *
     * @return bool
     */
    public function write(array $dataSet);

    /**
     * @param array<string, mixed> $dataSet
     *
     * @return bool
     */
    public function delete(array $dataSet);

    /**
     * @return string
     */
    public function getName();
}
