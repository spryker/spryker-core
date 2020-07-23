<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesRestApi\Processor\ConfigurableBundleTemplate;

use Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchRequestTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\ConfigurableBundlesRestApi\ConfigurableBundlesRestApiConfig;
use Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client\ConfigurableBundlesRestApiToConfigurableBundlePageSearchClientInterface;
use Spryker\Glue\ConfigurableBundlesRestApi\Dependency\Client\ConfigurableBundlesRestApiToConfigurableBundleStorageClientInterface;
use Spryker\Glue\ConfigurableBundlesRestApi\Processor\RestResponseBuilder\ConfigurableBundleTemplateRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Response;

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
                ->buildErrorRestResponse(
                    (new RestErrorMessageTransfer())
                        ->setCode(ConfigurableBundlesRestApiConfig::RESPONSE_CODE_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND)
                        ->setStatus(Response::HTTP_NOT_FOUND)
                        ->setDetail(ConfigurableBundlesRestApiConfig::RESPONSE_DETAIL_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND)
                );
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
        $formattedSearchResults = $this->configurableBundlePageSearchClient
            ->searchConfigurableBundleTemplates(new ConfigurableBundleTemplatePageSearchRequestTransfer());

        if (!$formattedSearchResults[static::FORMATTED_RESULT_KEY]) {
            return $this->configurableBundleTemplateRestResponseBuilder->createRestResponse();
        }

        $configurableBundleTemplateIds = [];
        foreach ($formattedSearchResults[static::FORMATTED_RESULT_KEY] as $configurableBundleTemplatePageSearchTransfer) {
            /** @var \Generated\Shared\Transfer\ConfigurableBundleTemplatePageSearchTransfer $configurableBundleTemplatePageSearchTransfer */
            $configurableBundleTemplateIds[] = $configurableBundleTemplatePageSearchTransfer->getFkConfigurableBundleTemplate();
        }

        $localeName = $restRequest->getMetadata()->getLocale();
        $configurableBundleTemplateStorageTransfers = $this->configurableBundleStorageClient
            ->getConfigurableBundleTemplateStorageTransfersByIds(
                array_filter($configurableBundleTemplateIds),
                $localeName
            );

        return $this->configurableBundleTemplateRestResponseBuilder
            ->buildConfigurableBundleTemplateCollectionRestResponse(
                $configurableBundleTemplateStorageTransfers,
                $localeName
            );
    }
}
