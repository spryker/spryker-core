<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship;

use Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Dependency\Client\CmsPagesContentProductAbstractListsResourceRelationshipToCmsStorageClientInterface;
use Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Dependency\Client\CmsPagesContentProductAbstractListsResourceRelationshipToStoreClientInterface;
use Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Dependency\RestApiResource\CmsPagesContentProductAbstractListsResourceRelationshipToContentProductAbstractListsRestApiResourceInterface;
use Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Processor\Expander\ContentProductAbstractListByCmsPageUuidResourceRelationshipExpander;
use Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Processor\Expander\ContentProductAbstractListByCmsPageUuidResourceRelationshipExpanderInterface;
use Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Processor\Reader\ContentProductAbstractListReader;
use Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Processor\Reader\ContentProductAbstractListReaderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CmsPagesContentProductAbstractListsResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Processor\Reader\ContentProductAbstractListReaderInterface
     */
    public function createContentProductAbstractListReader(): ContentProductAbstractListReaderInterface
    {
        return new ContentProductAbstractListReader(
            $this->getCmsStorageClient(),
            $this->getStoreClient(),
            $this->getContentProductAbstractListsRestApiResource()
        );
    }

    /**
     * @return \Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Processor\Expander\ContentProductAbstractListByCmsPageUuidResourceRelationshipExpanderInterface
     */
    public function createContentProductAbstractListByCmsPageUuidResourceRelationshipExpander(): ContentProductAbstractListByCmsPageUuidResourceRelationshipExpanderInterface
    {
        return new ContentProductAbstractListByCmsPageUuidResourceRelationshipExpander(
            $this->createContentProductAbstractListReader()
        );
    }

    /**
     * @return \Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Dependency\Client\CmsPagesContentProductAbstractListsResourceRelationshipToCmsStorageClientInterface
     */
    public function getCmsStorageClient(): CmsPagesContentProductAbstractListsResourceRelationshipToCmsStorageClientInterface
    {
        return $this->getProvidedDependency(CmsPagesContentProductAbstractListsResourceRelationshipDependencyProvider::CLIENT_CMS_STORAGE);
    }

    /**
     * @return \Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Dependency\Client\CmsPagesContentProductAbstractListsResourceRelationshipToStoreClientInterface
     */
    public function getStoreClient(): CmsPagesContentProductAbstractListsResourceRelationshipToStoreClientInterface
    {
        return $this->getProvidedDependency(CmsPagesContentProductAbstractListsResourceRelationshipDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Dependency\RestApiResource\CmsPagesContentProductAbstractListsResourceRelationshipToContentProductAbstractListsRestApiResourceInterface
     */
    public function getContentProductAbstractListsRestApiResource(): CmsPagesContentProductAbstractListsResourceRelationshipToContentProductAbstractListsRestApiResourceInterface
    {
        return $this->getProvidedDependency(CmsPagesContentProductAbstractListsResourceRelationshipDependencyProvider::RESOURCE_CONTENT_PRODUCT_ABSTRACT_LISTS_REST_API);
    }
}
