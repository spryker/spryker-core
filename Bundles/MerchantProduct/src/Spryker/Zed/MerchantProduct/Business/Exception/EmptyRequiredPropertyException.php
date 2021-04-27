<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProduct\Business\Exception;

use Exception;

class EmptyRequiredPropertyException extends Exception
{
    /**
     * @param string $propertyName
     */
    public function __construct(string $propertyName)
    {
        parent::__construct($this->buildMessage($propertyName));
    }

    /**
     * @param string $propertyName
     *
     * @return string
     */
    protected function buildMessage(string $propertyName): string
    {
        return sprintf('Empty required property "%s"', $propertyName);
    }
}
