<?php

namespace Spryker\Client\Category\Storage;


use Spryker\Client\Category\Dependency\Client\CategoryToStorageClientInterface;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;

class CategoryNodeStorage implements CategoryNodeStorageInterface
{

    /**
     * @var CategoryToStorageClientInterface
     */
    protected $storageClient;

    /**
     * @var KeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @param CategoryToStorageClientInterface $storageClient
     * @param KeyBuilderInterface $keyBuilder
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
     * @param string $data
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