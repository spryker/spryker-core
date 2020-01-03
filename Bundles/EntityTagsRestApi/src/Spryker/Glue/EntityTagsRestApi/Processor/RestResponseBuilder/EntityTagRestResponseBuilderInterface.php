<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestErrorMessageTransfer;

interface EntityTagRestResponseBuilderInterface
{
    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function createPreconditionRequiredError(): RestErrorMessageTransfer;

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function createPreconditionFailedError(): RestErrorMessageTransfer;
}
