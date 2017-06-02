<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabel;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductLabel\Storage\AbstractProductRelationReader;
use Spryker\Client\ProductLabel\Storage\LabelDictionaryReader;
use Spryker\Shared\ProductLabel\KeyBuilder\AbstractProductRelationKeyBuilder;
use Spryker\Shared\ProductLabel\KeyBuilder\LabelDictionaryKeyBuilder;

/**
 * @method ProductLabelConfig getConfig()
 */
class ProductLabelFactory extends AbstractFactory
{

    /**
     * @return \Spryker\Client\ProductLabel\Storage\AbstractProductRelationReaderInterface
     */
    public function createAbstractProductRelationReader()
    {
        return new AbstractProductRelationReader(
            $this->getStorageClient(),
            $this->createLabelDictionaryReader(),
            $this->createAbstractProductRelationKeyBuilder()
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
    protected function createLabelDictionaryReader()
    {
        return new LabelDictionaryReader(
            $this->getStorageClient(),
            $this->createLabelDictionaryKeyBuilder(),
            $this->getConfig()->getMaxNumberOfLabels()
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
    protected function createAbstractProductRelationKeyBuilder()
    {
        return new AbstractProductRelationKeyBuilder();
    }

}
