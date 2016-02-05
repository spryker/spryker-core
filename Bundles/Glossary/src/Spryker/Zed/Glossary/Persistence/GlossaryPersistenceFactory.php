<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Glossary\Persistence;

use Orm\Zed\Glossary\Persistence\SpyGlossaryKeyQuery;
use Orm\Zed\Glossary\Persistence\SpyGlossaryTranslationQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Spryker\Zed\Glossary\GlossaryConfig getConfig()
 * @method \Spryker\Zed\Glossary\Persistence\GlossaryQueryContainer getQueryContainer()
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

}
