<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Helper;

interface ProductConcreteSuperAttributeFilterHelperInterface
{
    /**
     * @param array $submittedAttributes
     *
     * @return array
     */
    public function getTransformedSubmittedSuperAttributes(array $submittedAttributes): array;
}
