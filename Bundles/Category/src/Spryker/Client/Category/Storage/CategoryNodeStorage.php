<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Category\Storage;

use Spryker\Client\Category\Dependency\Client\CategoryToStorageClientInterface;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;

class CategoryNodeStorage implements CategoryNodeStorageInterface
{
    /**
     * @var \Spryker\Client\Category\Dependency\Client\CategoryToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @param \Spryker\Client\Category\Dependency\Client\CategoryToStorageClientInterface $storageClient
     * @param \Spryker\Shared\KeyBuilder\KeyBuilderInterface $keyBuilder
     */
    public function __construct(
        CategoryToStorageClientInterface $storageClient,
        KeyBuilderInterface $keyBuilder
    ) {
        $this->storageClient = $storageClient;
        $this->keyBuilder = $keyBuilder;
    }

    /**
     * @param int $idCategoryNode
     * @param string $localeName
     *
     * @return string|null
     */
    public function getTemplatePathByNodeId($idCategoryNode, $localeName)
    {
        $key = $this->keyBuilder->generateKey($idCategoryNode, $localeName);
        $categoryNodeData = $this->storageClient->get($key);

        return $this->extractTemplatePath($categoryNodeData);
    }

    /**
     * @param array $categoryNodeData
     *
     * @return string|null
     */
    protected function extractTemplatePath($categoryNodeData)
    {
        $templatePath = null;

        if (isset($categoryNodeData['template_path'])) {
            $templatePath = $categoryNodeData['template_path'];
        }

        return $templatePath;
    }
}
