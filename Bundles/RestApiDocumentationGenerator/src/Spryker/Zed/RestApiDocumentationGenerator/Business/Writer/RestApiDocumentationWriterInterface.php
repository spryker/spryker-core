<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RestApiDocumentationGenerator\Business\Writer;

interface RestApiDocumentationWriterInterface
{
    /**
     * @param array $data
     *
     * @return void
     */
    public function write(array $data): void;
}
