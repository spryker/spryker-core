<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorApi\InvalidationVerifier;

use Generated\Shared\Transfer\DocumentationInvalidationVoterRequestTransfer;

class InvalidationVerifier implements InvalidationVerifierInterface
{
    /**
     * @var array<\Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\DocumentationInvalidationVoterPluginInterface>
     */
    protected array $documentationInvalidationVoterPlugins;

    /**
     * @param array<\Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\DocumentationInvalidationVoterPluginInterface> $documentationInvalidationVoterPlugins
     */
    public function __construct(array $documentationInvalidationVoterPlugins)
    {
        $this->documentationInvalidationVoterPlugins = $documentationInvalidationVoterPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\DocumentationInvalidationVoterRequestTransfer $documentationInvalidationVoterRequestTransfer
     *
     * @return bool
     */
    public function isInvalidated(DocumentationInvalidationVoterRequestTransfer $documentationInvalidationVoterRequestTransfer): bool
    {
        foreach ($this->documentationInvalidationVoterPlugins as $documentationInvalidationVoterPlugin) {
            if ($documentationInvalidationVoterPlugin->isInvalidated($documentationInvalidationVoterRequestTransfer)) {
                return true;
            }
        }

        return false;
    }
}
