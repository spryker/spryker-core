<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Glossary\Model;

use SprykerFeature\Shared\Library\Storage\Adapter\KeyValue\ReadInterface;

interface KVStoreAware
{
    /**
     * @param ReadInterface $kvReader
     * @return KVStoreAware
     */
    public function setKeyValueReader(ReadInterface $kvReader);
}
