<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Glossary\Code\KeyBuilder;

use Spryker\Shared\KeyBuilder\KeyBuilderTrait;

trait GlossaryKeyBuilder
{

    use KeyBuilderTrait;

    /**
     * @param string $glossaryKey
     *
     * @return string
     */
    protected function buildKey($glossaryKey)
    {
        return 'translation.' . $glossaryKey;
    }

    /**
     * @return string
     */
    public function getBundleName()
    {
        return 'glossary';
    }

}
