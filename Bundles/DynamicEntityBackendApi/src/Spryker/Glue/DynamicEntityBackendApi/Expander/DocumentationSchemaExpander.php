<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;
use Generated\Shared\Transfer\CustomRoutesContextTransfer;
use Spryker\Glue\DynamicEntityBackendApi\Controller\DynamicEntityBackendApiController;
use Spryker\Glue\DynamicEntityBackendApi\Mapper\GlueRequestDynamicEntityMapper;
use Spryker\Glue\DynamicEntityBackendApi\Mapper\GlueResponseDynamicEntityMapper;
use Spryker\Glue\DynamicEntityBackendApi\Processor\Reader\DynamicEntityReaderInterface;

class DocumentationSchemaExpander implements DocumentationSchemaExpanderInterface
{
    /**
     * @var string
     */
    protected const CONTROLLER = '_controller';

    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\Processor\Reader\DynamicEntityReaderInterface
     */
    protected DynamicEntityReaderInterface $dynamicEntityReader;

    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\Mapper\GlueRequestDynamicEntityMapper
     */
    protected GlueRequestDynamicEntityMapper $requestMapper;

    /**
     * @var \Spryker\Glue\DynamicEntityBackendApi\Mapper\GlueResponseDynamicEntityMapper
     */
    protected GlueResponseDynamicEntityMapper $responseMapper;

    /**
     * @param \Spryker\Glue\DynamicEntityBackendApi\Processor\Reader\DynamicEntityReaderInterface $dynamicEntityReader
     */
    public function __construct(
        DynamicEntityReaderInterface $dynamicEntityReader
    ) {
        $this->dynamicEntityReader = $dynamicEntityReader;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer
     */
    public function expand(ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer): ApiApplicationSchemaContextTransfer
    {
        $filteredCustomRoutes = $this->filterDynamicEntityControllerRouter($apiApplicationSchemaContextTransfer->getCustomRoutesContexts()->getArrayCopy());
        $apiApplicationSchemaContextTransfer->setCustomRoutesContexts(new ArrayObject($filteredCustomRoutes));

        $dynamicEntityConfigurationsTransfers = $this->dynamicEntityReader->getDynamicEntityConfigurationsWithChildRecursively();
        $apiApplicationSchemaContextTransfer->setDynamicEntityConfigurations(new ArrayObject($dynamicEntityConfigurationsTransfers));

        return $apiApplicationSchemaContextTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomRoutesContextTransfer $customRouteContextTransfer
     *
     * @return bool
     */
    protected function isDynamicEntityControllerRouteContext(CustomRoutesContextTransfer $customRouteContextTransfer): bool
    {
        return $customRouteContextTransfer->getDefaults()[static::CONTROLLER][0] === DynamicEntityBackendApiController::class;
    }

    /**
     * @param array<\Generated\Shared\Transfer\CustomRoutesContextTransfer> $customRouteContextTransfers
     *
     * @return array<\Generated\Shared\Transfer\CustomRoutesContextTransfer>
     */
    protected function filterDynamicEntityControllerRouter(array $customRouteContextTransfers): array
    {
        return array_filter($customRouteContextTransfers, function (CustomRoutesContextTransfer $customRouteContextTransfer) {
            return !$this->isDynamicEntityControllerRouteContext($customRouteContextTransfer);
        });
    }
}
