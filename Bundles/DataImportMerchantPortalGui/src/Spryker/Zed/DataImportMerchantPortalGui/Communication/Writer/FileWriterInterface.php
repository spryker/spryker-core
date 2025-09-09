<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchantPortalGui\Communication\Writer;

interface FileWriterInterface
{
    /**
     * @param mixed|resource $fileStream
     *
     * @return callable
     */
    public function write(mixed $fileStream): callable;

    /**
     * @param array<array<string, string>> $errors
     *
     * @return callable
     */
    public function writeErrors(array $errors): callable;
}
