<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilUuidGenerator\Dependency\External;

use Ramsey\Uuid\Uuid;

class UtilUuidGeneratorToRamseyUuidAdapter implements UtilUuidGeneratorToUuidGeneratorInterface
{
    /**
     * @param string $name
     *
     * @return string
     */
    public function generateUuid5FromObjectId(string $name): string
    {
        return Uuid::uuid5(Uuid::NAMESPACE_OID, $name)->toString();
    }
}
