<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\DocumentationInvalidationVoterRequestTransfer;

interface DocumentationInvalidationVoterPluginInterface
{
    /**
     * Specification:
     * - Checks if dynamic entity configuration is invalidated.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DocumentationInvalidationVoterRequestTransfer $documentationInvalidationVoterRequestTransfer
     *
     * @return bool
     */
    public function isInvalidated(DocumentationInvalidationVoterRequestTransfer $documentationInvalidationVoterRequestTransfer): bool;
}
