<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Glossary\Dependency;

interface GlossaryEvents
{
    /**
     * Specification
     * - This events will be used for key publishing
     *
     * @api
     */
    public const GLOSSARY_KEY_PUBLISH = 'Glossary.key.publish';

    /**
     * Specification
     * - This events will be used for key un-publishing
     *
     * @api
     */
    public const GLOSSARY_KEY_UNPUBLISH = 'Glossary.key.unpublish';

    /**
     * Specification
     * - This events will be used for spy_glossary_key entity creation
     *
     * @api
     */
    public const ENTITY_SPY_GLOSSARY_KEY_CREATE = 'Entity.spy_glossary_key.create';

    /**
     * Specification
     * - This events will be used for spy_glossary_key entity changes
     *
     * @api
     */
    public const ENTITY_SPY_GLOSSARY_KEY_UPDATE = 'Entity.spy_glossary_key.update';

    /**
     * Specification
     * - This events will be used for spy_glossary_key entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_GLOSSARY_KEY_DELETE = 'Entity.spy_glossary_key.delete';

    /**
     * Specification
     * - This events will be used for spy_glossary_translation entity creation
     *
     * @api
     */
    public const ENTITY_SPY_GLOSSARY_TRANSLATION_CREATE = 'Entity.spy_glossary_translation.create';

    /**
     * Specification
     * - This events will be used for spy_glossary_translation entity changes
     *
     * @api
     */
    public const ENTITY_SPY_GLOSSARY_TRANSLATION_UPDATE = 'Entity.spy_glossary_translation.update';

    /**
     * Specification
     * - This events will be used for spy_glossary_translation entity deletion
     *
     * @api
     */
    public const ENTITY_SPY_GLOSSARY_TRANSLATION_DELETE = 'Entity.spy_glossary_translation.delete';
}
