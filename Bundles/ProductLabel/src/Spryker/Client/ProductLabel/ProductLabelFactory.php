<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabel;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductLabel\Storage\LabelDictionaryReader;
use Spryker\Client\ProductLabel\Storage\ProductAbstractRelationReader;
use Spryker\Shared\ProductLabel\KeyBuilder\LabelDictionaryKeyBuilder;
use Spryker\Shared\ProductLabel\KeyBuilder\ProductAbstractRelationKeyBuilder;

class ProductLabelFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\ProductLabel\Storage\ProductAbstractRelationReaderInterface
     */
    public function createProductAbstractRelationReader()
    {
        return new ProductAbstractRelationReader(
            $this->getStorageClient(),
            $this->createLabelDictionaryReader(),
            $this->createProductAbstractRelationKeyBuilder()
        );
    }

    /**
     * @return \Spryker\Client\ProductLabel\Dependency\Client\ProductLabelToStorageInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(ProductLabelDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return \Spryker\Client\ProductLabel\Storage\LabelDictionaryReaderInterface
     */
    public function createLabelDictionaryReader()
    {
        return new LabelDictionaryReader(
            $this->getStorageClient(),
            $this->createLabelDictionaryKeyBuilder()
        );
    }

    /**
     * @return \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected function createLabelDictionaryKeyBuilder()
    {
        return new LabelDictionaryKeyBuilder();
    }

    /**
     * @return \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected function createProductAbstractRelationKeyBuilder()
    {
        return new ProductAbstractRelationKeyBuilder();
    }

}
