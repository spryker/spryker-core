<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\RestRequestValidator\Processor\Validator\Configuration;

interface RestRequestValidatorConfigReaderInterface
{
    /**
     * @param string $resourceType
     *
     * @return array
     */
    public function getValidationConfiguration(string $resourceType): array;
}
