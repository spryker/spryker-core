<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Glossary\Business\Key;

use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Glossary\Business\Exception\KeyExistsException;
use SprykerFeature\Zed\Glossary\Business\Exception\MissingKeyException;
use SprykerFeature\Zed\Glossary\Persistence\GlossaryQueryContainerInterface;
use SprykerFeature\Zed\Glossary\Persistence\Propel\SpyGlossaryKey;

class CarrierManager implements KeyManagerInterface
{

    /**
     * @param string $keyName
     *
     * @return int
     * @throws KeyExistsException
     * @throws PropelException
     */
    public function createCarrier($keyName)
    {
        $this->checkKeyDoesNotExist($keyName);

        $keyEntity = new SpyGlossaryKey();
        $keyEntity->setKey($keyName);
        $keyEntity->save();

        return $keyEntity->getPrimaryKey();
    }

    /**
     * @param string $keyName
     *
     * @throws KeyExistsException
     */
    protected function checkKeyDoesNotExist($keyName)
    {
        if ($this->hasKey($keyName)) {
            throw new KeyExistsException(
                sprintf(
                    'Tried to create key %s, but it already exists',
                    $keyName
                )
            );
        }
    }
}
