<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilUuidGenerator;

use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\UtilUuidGenerator\UtilUuidGeneratorServiceFactory getFactory()
 */
class UtilUuidGeneratorService extends AbstractService implements UtilUuidGeneratorServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $name
     *
     * @return string
     */
    public function generateUuid5FromObjectId(string $name): string
    {
        return $this->getFactory()
            ->getUuidGenerator()
            ->generateUuid5FromObjectId($name);
    }
}
