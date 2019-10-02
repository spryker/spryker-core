<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\EntityTag\Helper;

use Codeception\Module;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class EntityTagHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param string $resourceName
     * @param string $resourceId
     * @param array $attributes
     *
     * @return string|null
     */
    public function haveEntityTag(string $resourceName, string $resourceId, array $attributes): ?string
    {
        $entityTagClient = $this->getLocator()->entityTag()->client();
        $entityTagClient->write($resourceName, $resourceId, $attributes);

        return $entityTagClient->read($resourceName, $resourceId);
    }

    /**
     * @param string $resourceName
     * @param string $resourceId
     *
     * @return string|null
     */
    public function findResourceEntityTag(string $resourceName, string $resourceId): ?string
    {
        return $this->getLocator()->entityTag()->client()->read($resourceName, $resourceId);
    }
}
