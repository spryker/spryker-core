<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Cms\Service\Storage;

use SprykerFeature\Client\Storage\Service\StorageClientInterface;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;

class CmsBlockStorage implements CmsBlockStorageInterface
{

    /**
     * @var StorageClientInterface
     */
    private $storage;

    /**
     * @var KeyBuilderInterface
     */
    private $keyBuilder;

    /**
     * @var string
     */
    private $locale;

    /**
     * @param StorageClientInterface $storage
     * @param KeyBuilderInterface $keyBuilder
     * @param string $localeName
     */
    public function __construct($storage, $keyBuilder, $localeName)
    {
        $this->storage = $storage;
        $this->keyBuilder = $keyBuilder;
        $this->locale = $localeName;
    }

    /**
     * @param $blockName
     *
     * @return array
     */
    public function getBlockContent($blockName)
    {
        $key = $this->keyBuilder->generateKey($blockName, $this->locale);
        $block = $this->storage->get($key);
        return $block;
    }


}
