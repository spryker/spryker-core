<?php

namespace Spryker\Zed\CmsBlock\Dependency\QueryContainer;


use Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery;

interface CmsBlockToGlossaryQueryContainerInterface
{

    /**
     * @param string $keyName
     *
     * @return SpyGlossaryKeyQuery
     */
    public function queryKey($keyName);

}