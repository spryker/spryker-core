<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Dependency;

interface GlossaryEvents
{

    const GLOSSARY_KEY_PUBLISH = 'Glossary.key.publish';
    const GLOSSARY_KEY_UNPUBLISH = 'Glossary.key.unpublish';

    const ENTITY_SPY_GLOSSARY_KEY_CREATE = 'Entity.spy_glossary_key.create';
    const ENTITY_SPY_GLOSSARY_KEY_UPDATE = 'Entity.spy_glossary_key.update';
    const ENTITY_SPY_GLOSSARY_KEY_DELETE = 'Entity.spy_glossary_key.delete';

    const ENTITY_SPY_GLOSSARY_TRANSLATION_CREATE = 'Entity.spy_glossary_translation.create';
    const ENTITY_SPY_GLOSSARY_TRANSLATION_UPDATE = 'Entity.spy_glossary_translation.update';

}
