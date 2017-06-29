<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Dependency\QueryContainer;

class CmsBlockToGlossaryQueryContainerBridge implements CmsBlockToGlossaryQueryContainerInterface
{

    /**
     * @var \Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface
     */
    protected $glossaryQueryContainer;

    /**
     * @param \Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface $glossaryQueryContainer
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
