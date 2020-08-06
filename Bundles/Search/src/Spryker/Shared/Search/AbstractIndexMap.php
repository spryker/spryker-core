<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Search;

class AbstractIndexMap implements IndexMapInterface
{
    /**
     * @var array
     */
    protected $metadata = [];

    /**
     * @return string[]
     */
    public function getProperties()
    {
        return array_keys($this->metadata);
    }

    /**
     * @param string $propertyName
     *
     * @return string|null
     */
    public function getType($propertyName)
    {
        $metadata = $this->getMetadata($propertyName);

        return isset($metadata['type']) ? $metadata['type'] : null;
    }

    /**
     * @param string $propertyName
     *
     * @return array
     */
    public function getMetadata($propertyName)
    {
        return $this->metadata[$propertyName];
    }
}
