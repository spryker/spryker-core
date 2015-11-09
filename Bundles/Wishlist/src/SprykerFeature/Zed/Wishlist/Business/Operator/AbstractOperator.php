<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Business\Operator;

use Generated\Shared\Wishlist\ItemInterface;
use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistInterface;
use SprykerFeature\Zed\Wishlist\Business\Storage\StorageInterface;
use SprykerFeature\Zed\Wishlist\Dependency\PostSavePluginInterface;
use SprykerFeature\Zed\Wishlist\Dependency\PreSavePluginInterface;

abstract class AbstractOperator
{

    /**
     * @var PreSavePluginInterface[]
     */
    protected $preSavePlugins = [];

    /**
     * @var PostSavePluginInterface[]
     */
    protected $postSavePlugins = [];

    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * @var WishlistChangeInterface
     */
    private $wishlistChange;

    /**
     * @param StorageInterface $storage
     * @param WishlistChangeInterface $wishlistChange
     */
    public function __construct(StorageInterface $storage, WishlistChangeInterface $wishlistChange)
    {
        $this->storage = $storage;
        $this->wishlistChange = $wishlistChange;
    }

    /**
     * @return WishlistInterface
     */
    public function executeOperation()
    {
        $this->preSave($this->wishlistChange->getItems());
        $wishlist = $this->applyOperation($this->wishlistChange);
        $this->postSave($this->wishlistChange->getItems());

        return $wishlist;
    }

    /**
     * @param ItemInterface[] $items
     */
    protected function preSave(\ArrayObject $items)
    {
        $operationPlugins = $this->preSavePlugins[$this->getOperatorName()];

        foreach ($operationPlugins as $plugin) {
            $plugin->trigger($items);
        }
    }

    /**
     * @param ItemInterface[] $items
     */
    protected function postSave(\ArrayObject $items)
    {
        $operationPlugins = $this->postSavePlugins[$this->getOperatorName()];

        foreach ($operationPlugins as $plugin) {
            $plugin->trigger($items);
        }
    }

    /**
     * @param PreSavePluginInterface[] $preSavePlugins
     */
    public function setPreSavePlugins(array $preSavePlugins)
    {
        $this->preSavePlugins = $preSavePlugins;
    }

    /**
     * @param PostSavePluginInterface[] $postSavePlugins
     */
    public function setPostSavePlugins(array $postSavePlugins)
    {
        $this->postSavePlugins = $postSavePlugins;
    }

    /**
     * @param WishlistChangeInterface $wishlistItem
     */
    abstract protected function applyOperation(WishlistChangeInterface $wishlistItem);

    /**
     * @return string
     */
    abstract protected function getOperatorName();

}
