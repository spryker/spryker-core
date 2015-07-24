<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Business\Key;

use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Glossary\Business\Exception\KeyExistsException;
use SprykerFeature\Zed\Glossary\Business\Exception\MissingKeyException;
use SprykerFeature\Zed\Glossary\Persistence\Propel\SpyGlossaryKey;

interface KeyManagerInterface
{
    /**
     * @param array $data
     *
     * @return int
     * @throws KeyExistsException
     * @throws \Exception
     * @throws PropelException
     */
    public function createKey($data);

    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey($keyName);

    /**
     * @param string $keyName
     *
     * @return SpyGlossaryKey
     * @throws MissingKeyException
     */
    public function getKey($keyName);

    /**
     * @param array $data
     *
     * @return bool
     * @throws MissingKeyException
     */
    public function updateKey($data);

    /**
     * @param string $keyName
     *
     * @return bool
     */
    public function deleteKey($keyName);
}
