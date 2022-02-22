<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\KeyBuilder;

abstract class SharedResourceKeyBuilder implements KeyBuilderInterface
{
    use KeyBuilderTrait;

    /**
     * @param string $data
     *
     * @return string
     */
    protected function buildKey($data)
    {
        return $this->getResourceType() . '.' . $data;
    }

    /**
     * @return string
     */
    public function getBundleName()
    {
        return 'resource';
    }

    /**
     * @return string
     */
    abstract protected function getResourceType();
}
