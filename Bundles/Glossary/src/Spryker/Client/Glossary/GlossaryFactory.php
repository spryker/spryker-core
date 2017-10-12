<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Glossary;

use Spryker\Client\Glossary\KeyBuilder\GlossaryKeyBuilder;
use Spryker\Client\Glossary\Storage\GlossaryStorage;
use Spryker\Client\Kernel\AbstractFactory;

class GlossaryFactory extends AbstractFactory
{
    /**
     * @var \Spryker\Client\Glossary\Storage\GlossaryStorageInterface[]
     */
    protected static $translator = [];

    /**
     * @param string $localeName
     *
     * @return \Spryker\Client\Glossary\Storage\GlossaryStorageInterface
     */
    public function createTranslator($localeName)
    {
        return $this->getTranslatorInstance($localeName);
    }

    /**
     * @param string $localeName
     *
     * @return \Spryker\Client\Glossary\Storage\GlossaryStorageInterface
     */
    protected function getTranslatorInstance($localeName)
    {
        if (!isset(static::$translator[$localeName])) {
            static::$translator[$localeName] = $this->createGlossaryStorage($localeName);
        }

        return static::$translator[$localeName];
    }

    /**
     * @param string $localeName
     *
     * @return \Spryker\Client\Glossary\Storage\GlossaryStorageInterface
     */
    protected function createGlossaryStorage($localeName)
    {
        return new GlossaryStorage(
            $this->getStorage(),
            $this->createKeyBuilder(),
            $localeName
        );
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(GlossaryDependencyProvider::KV_STORAGE);
    }

    /**
     * @return \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected function createKeyBuilder()
    {
        return new GlossaryKeyBuilder();
    }
}
