<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorApi\InvalidationVerifier;

use Generated\Shared\Transfer\DocumentationInvalidationVoterRequestTransfer;

interface InvalidationVerifierInterface
{
    /**
     * @param \Generated\Shared\Transfer\DocumentationInvalidationVoterRequestTransfer $documentationInvalidationVoterRequestTransfer
     *
     * @return bool
     */
    public function isInvalidated(DocumentationInvalidationVoterRequestTransfer $documentationInvalidationVoterRequestTransfer): bool;
}
