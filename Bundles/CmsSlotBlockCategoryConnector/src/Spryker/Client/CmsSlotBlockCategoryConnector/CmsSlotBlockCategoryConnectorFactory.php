<?php

namespace Spryker\Client\CmsSlotBlockCategoryConnector;

use Spryker\Client\CmsSlotBlockCategoryConnector\Resolver\CategoryCmsSlotBlockConditionResolver;
use Spryker\Client\CmsSlotBlockCategoryConnector\Resolver\CategoryCmsSlotBlockConditionResolverInterface;
use Spryker\Client\Kernel\AbstractFactory;

class CmsSlotBlockCategoryConnectorFactory extends AbstractFactory
{
    public function createCategoryCmsSlotBlockConditionResolver(): CategoryCmsSlotBlockConditionResolverInterface
    {
        return new CategoryCmsSlotBlockConditionResolver();
    }
}
