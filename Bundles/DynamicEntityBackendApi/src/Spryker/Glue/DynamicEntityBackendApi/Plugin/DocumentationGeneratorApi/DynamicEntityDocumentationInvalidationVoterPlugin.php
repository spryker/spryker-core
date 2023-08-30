<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Plugin\DocumentationGeneratorApi;

use Generated\Shared\Transfer\DocumentationInvalidationVoterRequestTransfer;
use Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\DocumentationInvalidationVoterPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\DynamicEntityBackendApi\DynamicEntityBackendApiFactory getFactory()
 */
class DynamicEntityDocumentationInvalidationVoterPlugin extends AbstractPlugin implements DocumentationInvalidationVoterPluginInterface
{
    /**
     * Specification:
     * - Checks if dynamic entity configuration was changed within the provided interval.
     *
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DocumentationInvalidationVoterRequestTransfer $documentationInvalidationVoterRequestTransfer
     *
     * @return bool
     */
    public function isInvalidated(DocumentationInvalidationVoterRequestTransfer $documentationInvalidationVoterRequestTransfer): bool
    {
        return $this->getFactory()->createInvalidationVoter()->isInvalidated($documentationInvalidationVoterRequestTransfer);
    }
}
