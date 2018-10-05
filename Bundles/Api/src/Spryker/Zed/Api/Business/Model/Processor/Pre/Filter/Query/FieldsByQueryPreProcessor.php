<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Business\Model\Processor\Pre\Filter\Query;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\Api\Business\Model\Processor\Pre\PreProcessorInterface;

class FieldsByQueryPreProcessor implements PreProcessorInterface
{
    public const FIELDS = 'fields';

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    public function process(ApiRequestTransfer $apiRequestTransfer)
    {
        $queryStrings = $apiRequestTransfer->getQueryData();
        if (empty($queryStrings[static::FIELDS])) {
            return $apiRequestTransfer;
        }

        $fieldString = (string)$queryStrings[static::FIELDS];
        $fields = explode(',', $fieldString);

        $apiRequestTransfer->getFilter()->setFields($fields);

        return $apiRequestTransfer;
    }
}
