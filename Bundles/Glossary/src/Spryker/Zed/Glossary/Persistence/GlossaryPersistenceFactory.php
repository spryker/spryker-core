<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Persistence;

use Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery;
use Spryker\Zed\Glossary\Persistence\Propel\Mapper\GlossaryMapper;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Glossary\GlossaryConfig getConfig()
 * @method \Spryker\Zed\Glossary\Persistence\GlossaryQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Glossary\Persistence\GlossaryRepositoryInterface getRepository()
 */
class GlossaryPersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery
     */
    public function createGlossaryTranslationQuery()
    {
        return SpyGlossaryTranslationQuery::create();
    }

    /**
     * @return \Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery
     */
    public function createGlossaryKeyQuery()
    {
        return SpyGlossaryKeyQuery::create();
    }

    /**
     * @return \Spryker\Zed\Glossary\Persistence\Propel\Mapper\GlossaryMapper
     */
    public function createGlossaryMapper(): GlossaryMapper
    {
        return new GlossaryMapper();
    }
}
