<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Glossary\Code\KeyBuilder;

use Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderTrait;

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
