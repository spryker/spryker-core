<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagsRestApi\Processor\EntityTag;

interface EntityTagCheckerInterface
{
    /**
     * @param string $httpMethod
     * @param string $resourceType
     *
     * @return bool
     */
    public function isEntityTagValidationNeeded(string $httpMethod, string $resourceType): bool;

    /**
     * @param string $httpMethod
     * @param string $resourceType
     *
     * @return bool
     */
    public function isMethodApplicableForAddingEntityTagHeader(string $httpMethod, string $resourceType): bool;
}
