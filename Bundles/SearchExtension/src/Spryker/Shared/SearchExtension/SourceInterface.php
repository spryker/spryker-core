<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SearchExtension;

interface SourceInterface
{
    /**
     * @return array
     */
    public function getProperties();

    /**
     * @param string $propertyName
     *
     * @return string|null
     */
    public function getType($propertyName);

    /**
     * @param string $propertyName
     *
     * @return array
     */
    public function getMetadata($propertyName);
}
