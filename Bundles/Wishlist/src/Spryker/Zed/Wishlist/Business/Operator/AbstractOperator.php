<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Wishlist\Business\Operator;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\WishlistChangeTransfer;
use Generated\Shared\Transfer\WishlistTransfer;
use Spryker\Zed\Wishlist\Business\Storage\StorageInterface;
use Spryker\Zed\Wishlist\Dependency\PostSavePluginInterface;
use Spryker\Zed\Wishlist\Dependency\PreSavePluginInterface;

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
     * @var WishlistChangeTransfer
     */
    private $wishlistChange;

    /**
     * @param \Spryker\Zed\Wishlist\Business\Storage\StorageInterface $storage
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $wishlistChange
     */
    public function __construct(StorageInterface $storage, WishlistChangeTransfer $wishlistChange)
    {
        $this->storage = $storage;
        $this->wishlistChange = $wishlistChange;
    }

    /**
     * @return \Generated\Shared\Transfer\WishlistTransfer
     */
    public function executeOperation()
    {
        $this->preSave($this->wishlistChange->getItems());
        $wishlist = $this->applyOperation($this->wishlistChange);
        $this->postSave($this->wishlistChange->getItems());

        return $wishlist;
    }

    /**
     * @param ItemTransfer[] $items
     *
     * @return void
     */
    protected function preSave(\ArrayObject $items)
    {
        $operationPlugins = $this->preSavePlugins[$this->getOperatorName()];

        foreach ($operationPlugins as $plugin) {
            $plugin->trigger($items);
        }
    }

    /**
     * @param ItemTransfer[] $items
     *
     * @return void
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
     *
     * @return void
     */
    public function setPreSavePlugins(array $preSavePlugins)
    {
        $this->preSavePlugins = $preSavePlugins;
    }

    /**
     * @param PostSavePluginInterface[] $postSavePlugins
     *
     * @return void
     */
    public function setPostSavePlugins(array $postSavePlugins)
    {
        $this->postSavePlugins = $postSavePlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistChangeTransfer $wishlistItem
     */
    abstract protected function applyOperation(WishlistChangeTransfer $wishlistItem);

    /**
     * @return string
     */
    abstract protected function getOperatorName();

}
