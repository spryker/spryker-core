<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorApi\InvalidationVerifier;

use Generated\Shared\Transfer\DocumentationInvalidationVoterRequestTransfer;
use Spryker\Glue\DocumentationGeneratorApi\DocumentationGeneratorApiConfig;

class InvalidationVerifier implements InvalidationVerifierInterface
{
    /**
     * @var array<\Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\DocumentationInvalidationVoterPluginInterface>
     */
    protected array $documentationInvalidationVoterPlugins;

    /**
     * @var array<\Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ApiApplicationProviderPluginInterface>
     */
    protected array $apiApplicationProviderPlugins;

    /**
     * @var \Spryker\Glue\DocumentationGeneratorApi\DocumentationGeneratorApiConfig
     */
    protected DocumentationGeneratorApiConfig $documentationGeneratorApiConfig;

    /**
     * @param array<\Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\DocumentationInvalidationVoterPluginInterface> $documentationInvalidationVoterPlugins
     * @param array<\Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ApiApplicationProviderPluginInterface> $apiApplicationProviderPlugins
     * @param \Spryker\Glue\DocumentationGeneratorApi\DocumentationGeneratorApiConfig $documentationGeneratorApiConfig
     */
    public function __construct(
        array $documentationInvalidationVoterPlugins,
        array $apiApplicationProviderPlugins,
        DocumentationGeneratorApiConfig $documentationGeneratorApiConfig
    ) {
        $this->documentationInvalidationVoterPlugins = $documentationInvalidationVoterPlugins;
        $this->apiApplicationProviderPlugins = $apiApplicationProviderPlugins;
        $this->documentationGeneratorApiConfig = $documentationGeneratorApiConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\DocumentationInvalidationVoterRequestTransfer $documentationInvalidationVoterRequestTransfer
     * @param mixed $application
     *
     * @return bool
     */
    public function isInvalidated(
        DocumentationInvalidationVoterRequestTransfer $documentationInvalidationVoterRequestTransfer,
        mixed $application
    ): bool {
        if (!$this->hasSchemaFile($application)) {
            return true;
        }

        foreach ($this->documentationInvalidationVoterPlugins as $documentationInvalidationVoterPlugin) {
            if ($documentationInvalidationVoterPlugin->isInvalidated($documentationInvalidationVoterRequestTransfer)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param mixed $application
     *
     * @return bool
     */
    protected function hasSchemaFile(mixed $application): bool
    {
        foreach ($this->apiApplicationProviderPlugins as $apiApplicationProviderPlugin) {
            if (is_string($application) && $apiApplicationProviderPlugin->getName() !== $application) {
                continue;
            }
            if (!file_exists($this->documentationGeneratorApiConfig->getGeneratedFullFileName($apiApplicationProviderPlugin->getName()))) {
                return false;
            }
        }

        return true;
    }
}
