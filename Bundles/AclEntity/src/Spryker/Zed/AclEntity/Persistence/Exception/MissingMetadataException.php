<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Exception;

use Exception;

class MissingMetadataException extends Exception
{
    /**
     * @param string $entityClass
     */
    public function __construct(string $entityClass)
    {
        parent::__construct($this->buildMessage($entityClass));
    }

    /**
     * @param string $entityClass
     *
     * @return string
     */
    protected function buildMessage(string $entityClass): string
    {
        return sprintf(
            'No metadata definition found for %s',
            $entityClass,
        );
    }
}
