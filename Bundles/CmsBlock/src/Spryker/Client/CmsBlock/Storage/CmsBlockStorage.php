<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlock\Storage;

use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;

class CmsBlockStorage implements CmsBlockStorageInterface
{

    /**
     * @var \Spryker\Client\Storage\StorageClientInterface
     */
    protected $storage;

    /**
     * @var \Spryker\Shared\KeyBuilder\KeyBuilderInterface KeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @param \Spryker\Client\Storage\StorageClientInterface $storage
     * @param \Spryker\Shared\KeyBuilder\KeyBuilderInterface KeyBuilderInterface $keyBuilder
     */
    public function __construct(
        StorageClientInterface $storage,
        KeyBuilderInterface $keyBuilder
    ) {
        $this->storage = $storage;
        $this->keyBuilder = $keyBuilder;
    }

    /**
     * @param string[] $blockNames
     * @param string $localeName
     *
     * @return array
     */
    public function getBlocksByNames(array $blockNames, $localeName)
    {
        $searchKeys = [];

        foreach ($blockNames as $blockName) {
            $searchKeys[] = $this->keyBuilder->generateKey($blockName, $localeName);
        }

        $resultArray = $this->storage->getMulti($searchKeys) ?: [];
        $resultArray = array_filter($resultArray);

        $blocks = [];
        foreach ($resultArray as $key => $result) {
            $blocks[] = json_decode($result, true);
        }

        return $blocks;
    }

    /**
     * @param string $name
     * @param string $localeName
     *
     * @return string
     */
    public function generateBlockNameKey($name, $localeName)
    {
        $charsToReplace = ['"', "'", ' ', "\0", "\n", "\r"];

        return str_replace($charsToReplace, '-', mb_strtolower(trim($name)));
    }

    /**
     * //TODO Fix me
     * @param array $options
     * @param string $localName
     *
     * @return array
     */
    public function getBlockNamesByOptions(array $options, $localName)
    {
        $availableBlockNames = [];

        foreach ($options as $optionKey => $resources) {
            $searchKeys = [];
            $resources = (array)$resources;

            foreach ($resources as $id) {
                $searchKeys[] = $this->keyBuilder->generateKey($optionKey . '.' . $id, $localName);
            }

            $resultArray = $this->storage->getMulti($searchKeys);
            $resultArray = $this->decodeMulti($resultArray);

            if ($availableBlockNames) {
                $availableBlockNames = array_intersect($availableBlockNames, $resultArray);
            } else {
                $availableBlockNames = $resultArray;
            }
        }

        return $availableBlockNames;
    }

    /**
     * //TODO Fix me
     * @param array|null $array
     *
     * @return array
     */
    protected function decodeStringToArray($array)
    {
        if (!is_array($array)) {
            return [];
        }

        $array = array_filter($array);

        $resultArray = [];
        foreach ($array as $key => $result) {
            $resultArray = array_merge(
                $resultArray,
                json_decode($result, true)
            );
        }

        return $resultArray;
    }

}
