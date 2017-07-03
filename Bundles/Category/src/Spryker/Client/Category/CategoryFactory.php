<?php


namespace Spryker\Client\Category;


use Spryker\Client\Category\Dependency\Client\CategoryToStorageClientInterface;
use Spryker\Client\Category\KeyBuilder\CategoryNodeKeyBuilder;
use Spryker\Client\Category\Storage\CategoryNodeStorage;
use Spryker\Client\Category\Storage\CategoryNodeStorageInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CategoryFactory extends AbstractFactory
{

    /**
     * @return CategoryNodeStorageInterface
     */
    public function createCategoryNodeStorage()
    {
        return new CategoryNodeStorage(
            $this->getStorageClient(),
            $this->getCategoryNodeKeyBuilder()
        );
    }

    /**
     * @return CategoryToStorageClientInterface
     */
    protected function getStorageClient()
    {
        return $this->getProvidedDependency(CategoryDependencyProvider::CLIENT_STORAGE);
    }

    /**
     * @return CategoryNodeKeyBuilder
     */
    protected function getCategoryNodeKeyBuilder()
    {
        return new CategoryNodeKeyBuilder();
    }

}