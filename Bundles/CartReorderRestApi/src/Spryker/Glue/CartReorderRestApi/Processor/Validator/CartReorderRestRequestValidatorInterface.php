<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartReorderRestApi\Processor\Validator;

use Generated\Shared\Transfer\RestCartReorderRequestAttributesTransfer;

interface CartReorderRestRequestValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestCartReorderRequestAttributesTransfer $restCartReorderRequestAttributesTransfer
     *
     * @return list<\Generated\Shared\Transfer\RestErrorMessageTransfer>
     */
    public function validateRestRequestAttributes(RestCartReorderRequestAttributesTransfer $restCartReorderRequestAttributesTransfer): array;
}
