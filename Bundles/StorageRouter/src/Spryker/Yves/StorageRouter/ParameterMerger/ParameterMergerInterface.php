<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StorageRouter\ParameterMerger;

interface ParameterMergerInterface
{
    /**
     * @param array $requestParameters
     * @param array $generationParameters
     *
     * @return array
     */
    public function mergeParameters(array $requestParameters, array $generationParameters);
}
