<?php

namespace Spryker\Client\Category;


use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Category\CategoryFactory getFactory()
 */
class CategoryClient extends AbstractClient implements CategoryClientInterface
{

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idCategoryNode
     * @param string $localeName
     *
     * @return string
     */
    public function getTemplatePathByNodeId($idCategoryNode, $localeName)
    {
        return $this->getFactory()
            ->createCategoryNodeStorage()
            ->getTemplatePathByNodeId($idCategoryNode, $localeName);
    }

}