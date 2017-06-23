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
     * @param array $options
     * @param string $localeName
     *
     * @return array
     */
    public function getBlockNamesByOptions(array $options, $localeName)
    {
        $availableBlockNames = [];

        foreach ($options as $optionKey => $resources) {

            $resources = (array)$resources;
            $blockNames = $this->getBlockNamesForOption($optionKey, $resources, $localeName);

            $availableBlockNames = $availableBlockNames ?
                array_intersect($availableBlockNames, $blockNames) :
                $blockNames;
        }

        return $availableBlockNames;
    }

    /**
     * @param $optionKey
     * @param $idResources
     * @param $localeName
     *
     * @return string[]
     */
    protected function getBlockNamesForOption($optionKey, $idResources, $localeName)
    {
        $searchKeys = [];

        foreach ($idResources as $id) {
            $searchKeys[] = $this->keyBuilder->generateKey($optionKey . '.' . $id, $localeName);
        }

        $blockNames = $this->storage->getMulti($searchKeys);
        $blockNames = $this->decodeMultiResultToArrays($blockNames);

        return $blockNames;
    }

    /**
     * @param array|null $array
     *
     * @return array
     */
    protected function decodeMultiResultToArrays($array)
    {
        if (!is_array($array)) {
            return [];
        }

        $resultArray = [];
        foreach ($array as $key => $result) {
            $resultArray = array_merge($resultArray, json_decode($result, true));
        }

        return $resultArray;
    }

}
