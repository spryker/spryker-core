<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\GlossaryStorage;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class GlossaryStorageConfig extends AbstractBundleConfig
{
    /**
     * Defines queue name as used for processing translation messages.
     */
    public const PUBLISH_TRANSLATION = 'publish.translation';

    /**
     * Defines queue name as used for processing translation messages.
     */
    public const SYNC_STORAGE_TRANSLATION = 'sync.storage.translation';

    /**
     * Defines queue name as used for processing translation error messages.
     */
    public const SYNC_STORAGE_TRANSLATION_ERROR = 'sync.storage.translation.error';

    /**
     * Defines resource name, that will be used for key generation.
     */
    public const TRANSLATION_RESOURCE_NAME = 'translation';

    /**
     * This events that will be used for key writing.
     */
    public const GLOSSARY_KEY_PUBLISH_WRITE = 'Glossary.key.publish';

    /**
     * This events that will be used for key deleting.
     */
    public const GLOSSARY_KEY_PUBLISH_DELETE = 'Glossary.key.unpublish';

    /**
     * This events will be used for spy_glossary_key entity creation.
     */
    public const ENTITY_SPY_GLOSSARY_KEY_CREATE = 'Entity.spy_glossary_key.create';

    /**
     * This events will be used for spy_glossary_key entity changes.
     */
    public const ENTITY_SPY_GLOSSARY_KEY_UPDATE = 'Entity.spy_glossary_key.update';

    /**
     * This events will be used for spy_glossary_key entity deletion.
     */
    public const ENTITY_SPY_GLOSSARY_KEY_DELETE = 'Entity.spy_glossary_key.delete';

    /**
     * This events will be used for spy_glossary_translation entity creation.
     */
    public const ENTITY_SPY_GLOSSARY_TRANSLATION_CREATE = 'Entity.spy_glossary_translation.create';

    /**
     * This events will be used for spy_glossary_translation entity changes.
     */
    public const ENTITY_SPY_GLOSSARY_TRANSLATION_UPDATE = 'Entity.spy_glossary_translation.update';

    /**
     * This events will be used for spy_glossary_translation entity deletion.
     */
    public const ENTITY_SPY_GLOSSARY_TRANSLATION_DELETE = 'Entity.spy_glossary_translation.delete';
}
