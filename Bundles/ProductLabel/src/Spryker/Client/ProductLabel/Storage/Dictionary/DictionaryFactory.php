<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductLabel\Storage\Dictionary;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductLabel\ProductLabelDependencyProvider;
use Spryker\Shared\ProductLabel\KeyBuilder\LabelDictionaryKeyBuilder;

class DictionaryFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductLabel\Storage\Dictionary\LabelDictionaryInterface
     */
    public function createDictionaryByIdProductLabel()
    {
        return new LabelDictionary(
            $this->getStorageClient(),
            $this->createLabelDictionaryKeyBuilder(),
            $this->createKeyByIdProductLabelStrategy(),
        );
    }

    /**
     * @return \Spryker\Client\ProductLabel\Storage\Dictionary\LabelDictionaryInterface
     */
    public function createDictionaryByLocalizedName()
    {
        return new LabelDictionary(
            $this->getStorageClient(),
            $this->createLabelDictionaryKeyBuilder(),
            $this->createKeyByLocalizedNameStrategy(),
        );
    }

    /**
     * @return \Spryker\Client\ProductLabel\Storage\Dictionary\LabelDictionaryInterface
     */
    public function createDictionaryByName()
    {
        return new LabelDictionary(
            $this->getStorageClient(),
            $this->createLabelDictionaryKeyBuilder(),
            $this->createKeyByNameStrategy(),
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
     * @return \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected function createLabelDictionaryKeyBuilder()
    {
        return new LabelDictionaryKeyBuilder();
    }

    /**
     * @return \Spryker\Client\ProductLabel\Storage\Dictionary\KeyStrategyInterface
     */
    protected function createKeyByIdProductLabelStrategy()
    {
        return new KeyByIdProductLabelStrategy();
    }

    /**
     * @return \Spryker\Client\ProductLabel\Storage\Dictionary\KeyStrategyInterface
     */
    protected function createKeyByLocalizedNameStrategy()
    {
        return new KeyByLocalizedNameStrategy();
    }

    /**
     * @return \Spryker\Client\ProductLabel\Storage\Dictionary\KeyStrategyInterface
     */
    protected function createKeyByNameStrategy()
    {
        return new KeyByNameStrategy();
    }
}
