<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GlossaryStorage\Dependency\QueryContainer;

use Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery;

class GlossaryStorageToGlossaryQueryContainerBridge implements GlossaryStorageToGlossaryQueryContainerInterface
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
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function queryTranslations()
    {
        return $this->glossaryQueryContainer->queryTranslations();
    }

    /**
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function queryKeys(): SpyGlossaryKeyQuery
    {
        return $this->glossaryQueryContainer->queryKeys();
    }
}
