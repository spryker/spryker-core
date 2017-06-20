<?php


namespace Spryker\Zed\CmsBlockCategoryConnector\Dependency\QueryContainer;


class TouchQueryContainerBridge implements TouchQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @param \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface $touchQueryContainer
     */
    public function __construct($touchQueryContainer)
    {
        $this->touchQueryContainer = $touchQueryContainer;
    }

}