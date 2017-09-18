<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Dependency;

use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryKeyTableMap;
use Orm\Zed\Glossary\Persistence\Map\SpyGlossaryTranslationTableMap;

interface GlossaryEvents
{

    const GLOSSARY_KEY_PUBLISH = 'Glossary.key.publish';
    const GLOSSARY_KEY_UNPUBLISH = 'Glossary.key.unpublish';

    const ENTITY_SPY_GLOSSARY_KEY_CREATE = 'Entity.' . SpyGlossaryKeyTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_GLOSSARY_KEY_UPDATE = 'Entity.' . SpyGlossaryKeyTableMap::TABLE_NAME . '.update';
    const ENTITY_SPY_GLOSSARY_KEY_DELETE = 'Entity.' . SpyGlossaryKeyTableMap::TABLE_NAME . '.delete';

    const ENTITY_SPY_GLOSSARY_TRANSLATION_CREATE = 'Entity.' . SpyGlossaryTranslationTableMap::TABLE_NAME . '.create';
    const ENTITY_SPY_GLOSSARY_TRANSLATION_UPDATE = 'Entity.' . SpyGlossaryTranslationTableMap::TABLE_NAME . '.update';

}
