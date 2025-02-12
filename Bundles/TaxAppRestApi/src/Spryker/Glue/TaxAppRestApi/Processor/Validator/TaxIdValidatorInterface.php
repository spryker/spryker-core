<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TaxAppRestApi\Processor\Validator;

use Generated\Shared\Transfer\RestTaxAppValidationAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

interface TaxIdValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestTaxAppValidationAttributesTransfer $restTaxAppValidationAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function validate(RestTaxAppValidationAttributesTransfer $restTaxAppValidationAttributesTransfer): RestResponseInterface;
}
