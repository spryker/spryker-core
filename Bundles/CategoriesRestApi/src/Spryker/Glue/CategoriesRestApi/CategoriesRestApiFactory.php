<?php
/**
 * Created by PhpStorm.
 * User: poidenko
 * Date: 7/23/18
 * Time: 5:28 PM
 */

namespace Spryker\Glue\CategoriesRestApi;

use Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToCategoryStorageClientInterface;
use Spryker\Glue\CategoriesRestApi\Processor\Categories\CategoriesRestApiReader;
use Spryker\Glue\CategoriesRestApi\Processor\Categories\CategoriesRestApiReaderInterface;
use Spryker\Glue\CategoriesRestApi\Processor\Mapper\CategoriesResourceMapper;
use Spryker\Glue\CategoriesRestApi\Processor\Mapper\CategoriesResourceMapperInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CategoriesRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CategoriesRestApi\Processor\Categories\CategoriesRestApiReaderInterface
     */
    public function createCategoriesReader(): CategoriesRestApiReaderInterface
    {
        return new CategoriesRestApiReader(
            $this->getResourceBuilder(),
            $this->getCategoryStorageClient(),
            $this->createCategoriesResourceMapper()
        );
    }

    /**
     * @return \Spryker\Glue\CategoriesRestApi\Dependency\Client\CategoriesRestApiToCategoryStorageClientInterface
     */
    protected function getCategoryStorageClient(): CategoriesRestApiToCategoryStorageClientInterface
    {
        return $this->getProvidedDependency(CategoriesRestApiDependencyProvider::CATEGORY_STORAGE_CLIENT);
    }

    /**
     * @return \Spryker\Glue\CategoriesRestApi\Processor\Mapper\CategoriesResourceMapperInterface
     */
    protected function createCategoriesResourceMapper(): CategoriesResourceMapperInterface
    {
        return new CategoriesResourceMapper();
    }
}
