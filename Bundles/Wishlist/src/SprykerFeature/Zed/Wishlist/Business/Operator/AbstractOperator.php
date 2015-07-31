<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Wishlist\Business\Operator;

use Generated\Shared\Wishlist\WishlistChangeInterface;
use Generated\Shared\Wishlist\WishlistInterface;
use Generated\Shared\Wishlist\WishlistItemInterface;
use SprykerFeature\Zed\Wishlist\Business\Storage\StorageInterface;

abstract class AbstractOperator
{
    /**
     * @var array
     */
    protected $preSavePlugins = [];

    /**
     * @var array
     */
    protected $postSavePlugins = [];

    /**
     * @var StorageInterface
     */
    private $storage;

    /**
     * @var WishlistChangeInterface
     */
    private $wishlistChange;

    /**
     * @param StorageInterface        $storage
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
        $this->preSave($this->wishlistChange);
        $wishlist = $this->applyOperation($this->wishlistChange);
        $this->postSave($wishlist);

        return $wishlist;
    }

    protected function preSave($wishlistChange)
    {
        foreach ($this->preSavePlugins as $plugin) {
            $plugin->trigger($wishlistChange);
        }
    }

    protected function postSave($wishlist)
    {
        foreach ($this->postSavePlugins as $plugin) {
            $plugin->trigger($wishlist);
        }
    }

    /**
     * @param WishlistChangeInterface $wishlistItem
     *
     */
    abstract protected function applyOperation(WishlistChangeInterface $wishlistItem);


}
