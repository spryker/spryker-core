<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Processor\Reader;

use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchRequestTransfer;
use Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client\ConfigurableBundlesRestApiToConfigurableBundlePageSearchClientInterface;
use Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client\ConfigurableBundlesRestApiToConfigurableBundleStorageClientInterface;
use Spryker\Glue\ConfigurableBundlesRestApi\Processor\RestResponseBuilder\ConfigurableBundleTemplateRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ConfigurableBundleTemplateReader implements ConfigurableBundleTemplateReaderInterface
{
    /**
     * @uses \Spryker\Client\ConfigurableBundlePageSearch\Plugin\Elasticsearch\ResultFormatter\ConfigurableBundleTemplatePageSearchResultFormatterPlugin::NAME
     */
    protected const FORMATTED_RESULT_KEY = 'ConfigurableBundleTemplateCollection';

    /**
     * @var \Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client\ConfigurableBundlesRestApiToConfigurableBundleStorageClientInterface
     */
    protected $configurableBundleStorageClient;

    /**
     * @var \Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client\ConfigurableBundlesRestApiToConfigurableBundlePageSearchClientInterface
     */
    protected $configurableBundlePageSearchClient;

    /**
     * @var \Spryker\Glue\ConfigurableBundlesRestApi\Processor\RestResponseBuilder\ConfigurableBundleTemplateRestResponseBuilderInterface
     */
    protected $configurableBundleTemplateRestResponseBuilder;

    /**
     * @param \Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client\ConfigurableBundlesRestApiToConfigurableBundleStorageClientInterface $configurableBundleStorageClient
     * @param \Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client\ConfigurableBundlesRestApiToConfigurableBundlePageSearchClientInterface $configurableBundlePageSearchClient
     * @param \Spryker\Glue\ConfigurableBundlesRestApi\Processor\RestResponseBuilder\ConfigurableBundleTemplateRestResponseBuilderInterface $configurableBundleTemplateRestResponseBuilder
     */
    public function __construct(
        ConfigurableBundlesRestApiToConfigurableBundleStorageClientInterface $configurableBundleStorageClient,
        ConfigurableBundlesRestApiToConfigurableBundlePageSearchClientInterface $configurableBundlePageSearchClient,
        ConfigurableBundleTemplateRestResponseBuilderInterface $configurableBundleTemplateRestResponseBuilder
    ) {
        $this->configurableBundleStorageClient = $configurableBundleStorageClient;
        $this->configurableBundlePageSearchClient = $configurableBundlePageSearchClient;
        $this->configurableBundleTemplateRestResponseBuilder = $configurableBundleTemplateRestResponseBuilder;
    }

    /**
     * @param string $uuidConfigurableBundleTemplate
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getConfigurableBundleTemplate(
        string $uuidConfigurableBundleTemplate,
        RestRequestInterface $restRequest
    ): RestResponseInterface {
        $localeName = $restRequest->getMetadata()->getLocale();
        $configurableBundleTemplateStorageTransfer = $this->configurableBundleStorageClient
            ->findConfigurableBundleTemplateStorageByUuid($uuidConfigurableBundleTemplate, $localeName);

        if (!$configurableBundleTemplateStorageTransfer) {
            return $this->configurableBundleTemplateRestResponseBuilder
                ->buildConfigurableBundleTemplateNotFoundErrorRestResponse();
        }

        return $this->configurableBundleTemplateRestResponseBuilder
            ->buildConfigurableBundleTemplateRestResponse($configurableBundleTemplateStorageTransfer, $localeName);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getConfigurableBundleTemplateCollection(RestRequestInterface $restRequest): RestResponseInterface
    {
        $configurableBundleTemplateIds = $this->getAllConfigurableBundleTemplateIds();

        if (!$configurableBundleTemplateIds) {
            return $this->configurableBundleTemplateRestResponseBuilder->createRestResponse();
        }

        $configurableBundleTemplateStorageTransfers = $this->configurableBundleStorageClient
            ->getBulkConfigurableBundleTemplateStorage(
                $configurableBundleTemplateIds,
                $restRequest->getMetadata()->getLocale()
            );

        return $this->configurableBundleTemplateRestResponseBuilder
            ->buildConfigurableBundleTemplateCollectionRestResponse(
                $configurableBundleTemplateStorageTransfers,
                $restRequest->getMetadata()->getLocale()
            );
    }

    /**
     * @return int[]
     */
    protected function getAllConfigurableBundleTemplateIds(): array
    {
        $formattedSearchResults = $this->configurableBundlePageSearchClient
            ->searchConfigurableBundleTemplates(new ConfigurableBundleTemplatePageSearchRequestTransfer());

        if (!isset($formattedSearchResults[static::FORMATTED_RESULT_KEY])) {
            return [];
        }

        $configurableBundleTemplateIds = [];
        foreach ($formattedSearchResults[static::FORMATTED_RESULT_KEY] as $configurableBundleTemplatePageSearchTransfer) {
            /** @var \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer */
            $configurableBundleTemplateIds[] = $configurableBundleTemplatePageSearchTransfer->getFkConfigurableBundleTemplate();
        }

        return array_filter($configurableBundleTemplateIds);
    }
}
