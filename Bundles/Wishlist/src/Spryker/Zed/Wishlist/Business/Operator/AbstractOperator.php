<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Wishlist\Business\Operator;

use Generated\Shared\Transfer\WishlistChangeTransfer;
use Spryker\Zed\Wishlist\Business\Storage\StorageInterface;

abstract class AbstractOperator
{

    /**
     * @var \Spryker\Zed\Wishlist\Dependency\PreSavePluginInterface[]
     */
    protected $preSavePlugins = [];

    /**
     * @var \Spryker\Zed\Wishlist\Dependency\PostSavePluginInterface[]
     */
    protected $postSavePlugins = [];

    /**
     * @var \Spryker\Zed\Wishlist\Business\Storage\StorageInterface
     */
    protected $storage;

    /**
     * @var \Generated\Shared\Transfer\WishlistChangeTransfer
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
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
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
     * @param \Generated\Shared\Transfer\ItemTransfer[] $items
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
     * @param \Spryker\Zed\Wishlist\Dependency\PreSavePluginInterface[] $preSavePlugins
     *
     * @return void
     */
    public function setPreSavePlugins(array $preSavePlugins)
    {
        $this->preSavePlugins = $preSavePlugins;
    }

    /**
     * @param \Spryker\Zed\Wishlist\Dependency\PostSavePluginInterface[] $postSavePlugins
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
