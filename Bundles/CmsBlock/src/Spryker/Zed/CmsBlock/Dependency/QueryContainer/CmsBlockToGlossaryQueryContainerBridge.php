<?php

namespace Spryker\Zed\CmsBlock\Dependency\QueryContainer;


use Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;

class CmsBlockToGlossaryQueryContainerBridge implements CmsBlockToGlossaryQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface
     */
    protected $glossaryQueryContainer;


    /**
     * @param GlossaryQueryContainerInterface $glossaryQueryContainer
     */
    public function __construct($glossaryQueryContainer)
    {
        $this->glossaryQueryContainer = $glossaryQueryContainer;
    }

    /**
     * @param string $keyName
     *
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryKey($keyName)
    {
        return $this->glossaryQueryContainer->queryKey($keyName);
    }

}