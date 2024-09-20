<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DocumentationGeneratorApi\Generator;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Spryker\Glue\DocumentationGeneratorApi\Dependency\Client\DocumentationGeneratorApiToStorageClientInterface;
use Spryker\Glue\DocumentationGeneratorApi\Dependency\External\DocumentationGeneratorApiToFilesystemInterface;
use Spryker\Glue\DocumentationGeneratorApi\Dependency\Service\DocumentationGenerationApiToUtilEncodingServiceInterface;
use Spryker\Glue\DocumentationGeneratorApi\DocumentationGeneratorApiConfig;
use Spryker\Glue\DocumentationGeneratorApi\Expander\ContextExpanderCollectionInterface;
use Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ApiApplicationProviderPluginInterface;
use Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ContentGeneratorStrategyPluginInterface;

class DocumentationGenerator implements DocumentationGeneratorInterface
{
    /**
     * @var string
     */
    protected const FILE_DATA = 'file_data';

    /**
     * @var string
     */
    protected const CREATED_AT = 'created_at';

    /**
     * @var array<\Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ApiApplicationProviderPluginInterface>
     */
    protected $apiApplicationProviderPlugins;

    /**
     * @var \Spryker\Glue\DocumentationGeneratorApi\Expander\ContextExpanderCollectionInterface
     */
    protected $contextExpanderCollection;

    /**
     * @var \Spryker\Glue\DocumentationGeneratorApi\Dependency\External\DocumentationGeneratorApiToFilesystemInterface
     */
    protected $filesystem;

    /**
     * @var \Spryker\Glue\DocumentationGeneratorApi\DocumentationGeneratorApiConfig
     */
    protected $documentationGeneratorApiConfig;

    /**
     * @var array<\Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\SchemaFormatterPluginInterface>
     */
    protected $schemaFormatterPlugins;

    /**
     * @var \Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ContentGeneratorStrategyPluginInterface
     */
    protected $contentGeneratorStrategyPlugin;

    /**
     * @var \Spryker\Glue\DocumentationGeneratorApi\Dependency\Client\DocumentationGeneratorApiToStorageClientInterface
     */
    protected DocumentationGeneratorApiToStorageClientInterface $storageClient;

    /**
     * @var \Spryker\Glue\DocumentationGeneratorApi\Dependency\Service\DocumentationGenerationApiToUtilEncodingServiceInterface
     */
    protected DocumentationGenerationApiToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param array<\Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ApiApplicationProviderPluginInterface> $apiApplicationProviderPlugins
     * @param \Spryker\Glue\DocumentationGeneratorApi\Expander\ContextExpanderCollectionInterface $contextExpanderCollection
     * @param \Spryker\Glue\DocumentationGeneratorApi\Dependency\External\DocumentationGeneratorApiToFilesystemInterface $filesystem
     * @param \Spryker\Glue\DocumentationGeneratorApi\DocumentationGeneratorApiConfig $documentationGeneratorApiConfig
     * @param array<\Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\SchemaFormatterPluginInterface> $schemaFormatterPlugins
     * @param \Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ContentGeneratorStrategyPluginInterface $contentGeneratorStrategyPlugin
     * @param \Spryker\Glue\DocumentationGeneratorApi\Dependency\Client\DocumentationGeneratorApiToStorageClientInterface $storageClient
     * @param \Spryker\Glue\DocumentationGeneratorApi\Dependency\Service\DocumentationGenerationApiToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        array $apiApplicationProviderPlugins,
        ContextExpanderCollectionInterface $contextExpanderCollection,
        DocumentationGeneratorApiToFilesystemInterface $filesystem,
        DocumentationGeneratorApiConfig $documentationGeneratorApiConfig,
        array $schemaFormatterPlugins,
        ContentGeneratorStrategyPluginInterface $contentGeneratorStrategyPlugin,
        DocumentationGeneratorApiToStorageClientInterface $storageClient,
        DocumentationGenerationApiToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->apiApplicationProviderPlugins = $apiApplicationProviderPlugins;
        $this->contextExpanderCollection = $contextExpanderCollection;
        $this->filesystem = $filesystem;
        $this->documentationGeneratorApiConfig = $documentationGeneratorApiConfig;
        $this->schemaFormatterPlugins = $schemaFormatterPlugins;
        $this->contentGeneratorStrategyPlugin = $contentGeneratorStrategyPlugin;
        $this->storageClient = $storageClient;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param array<string> $applications
     *
     * @return void
     */
    public function generateDocumentation(array $applications = []): void
    {
        foreach ($this->apiApplicationProviderPlugins as $apiApplicationProviderPlugin) {
            if (!$applications || in_array($apiApplicationProviderPlugin->getName(), $applications)) {
                $apiApplicationSchemaContextTransfer = $this->initContext($apiApplicationProviderPlugin);
                $apiApplicationSchemaContextTransfer = $this->expandContext($apiApplicationProviderPlugin, $apiApplicationSchemaContextTransfer);
                $formattedData = $this->formatContext($apiApplicationSchemaContextTransfer);
                $documentationContent = $this->contentGeneratorStrategyPlugin->generateContent($formattedData);

                $this->filesystem->dumpFile($apiApplicationSchemaContextTransfer->getFileNameOrFail(), $documentationContent);
                $time = filemtime($apiApplicationSchemaContextTransfer->getFileNameOrFail());

                /** @var string $apiSchemaStorageData */
                $apiSchemaStorageData = $this->utilEncodingService->encodeJson(
                    [
                        static::FILE_DATA => $documentationContent,
                        static::CREATED_AT => $time,
                    ],
                );

                $this->storageClient->set(
                    sprintf(
                        $this->documentationGeneratorApiConfig->getApiSchemaStorageKeyPattern(),
                        strtolower($apiApplicationProviderPlugin->getName()),
                    ),
                    $apiSchemaStorageData,
                );
            }
        }
    }

    /**
     * @param \Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ApiApplicationProviderPluginInterface $apiApplicationProviderPlugin
     *
     * @return \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer
     */
    protected function initContext(ApiApplicationProviderPluginInterface $apiApplicationProviderPlugin): ApiApplicationSchemaContextTransfer
    {
        $applicationName = $apiApplicationProviderPlugin->getName();

        return (new ApiApplicationSchemaContextTransfer())
            ->setApplication($applicationName)
            ->setFileName($this->documentationGeneratorApiConfig->getGeneratedFullFileName($applicationName));
    }

    /**
     * @param \Spryker\Glue\DocumentationGeneratorApiExtension\Dependency\Plugin\ApiApplicationProviderPluginInterface $apiApplicationProviderPlugin
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer
     */
    protected function expandContext(
        ApiApplicationProviderPluginInterface $apiApplicationProviderPlugin,
        ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
    ): ApiApplicationSchemaContextTransfer {
        $contextExpanderPlugins = $this->contextExpanderCollection->getExpanders($apiApplicationProviderPlugin->getName());
        foreach ($contextExpanderPlugins as $contextExpanderPlugin) {
            $apiApplicationSchemaContextTransfer = $contextExpanderPlugin->expand($apiApplicationSchemaContextTransfer);
        }

        return $apiApplicationSchemaContextTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return array<mixed>
     */
    protected function formatContext(ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer): array
    {
        $formattedDocumentationData = [];

        foreach ($this->schemaFormatterPlugins as $schemaFormatterPlugin) {
            $formattedDocumentationData = $schemaFormatterPlugin->format(
                $formattedDocumentationData,
                $apiApplicationSchemaContextTransfer,
            );
        }

        return $formattedDocumentationData;
    }
}
