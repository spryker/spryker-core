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
        return new LabelDictionaryByIdProductLabel($this->getStorageClient(), $this->createLabelDictionaryKeyBuilder());
    }

    /**
     * @return \Spryker\Client\ProductLabel\Storage\Dictionary\LabelDictionaryInterface
     */
    public function createDictionaryByLocalizedName()
    {
        return new LabelDictionaryByLocalizedName($this->getStorageClient(), $this->createLabelDictionaryKeyBuilder());
    }

    /**
     * @return \Spryker\Client\ProductLabel\Storage\Dictionary\LabelDictionaryInterface
     */
    public function createDictionaryByName()
    {
        return new LabelDictionaryByName($this->getStorageClient(), $this->createLabelDictionaryKeyBuilder());
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

}
