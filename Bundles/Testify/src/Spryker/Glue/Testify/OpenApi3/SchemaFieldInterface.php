<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Testify\OpenApi3;

interface SchemaFieldInterface
{
    /**
     * @param mixed $content
     *
     * @return $this
     */
    public function hydrate($content);

    /**
     * @return mixed
     */
    public function export();
}
