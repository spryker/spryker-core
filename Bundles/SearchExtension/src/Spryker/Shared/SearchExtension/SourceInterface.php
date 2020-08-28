<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\SearchExtension;

interface SourceInterface
{
    /**
     * Specification:
     * - Returns source map properties.
     *
     * @return string[]
     */
    public function getProperties();

    /**
     * Specification:
     * - Returns source type.
     *
     * @param string $propertyName
     *
     * @return string|null
     */
    public function getType($propertyName);

    /**
     * Specification:
     * - Returns source map metadata.
     *
     * @param string $propertyName
     *
     * @return array
     */
    public function getMetadata($propertyName);
}
