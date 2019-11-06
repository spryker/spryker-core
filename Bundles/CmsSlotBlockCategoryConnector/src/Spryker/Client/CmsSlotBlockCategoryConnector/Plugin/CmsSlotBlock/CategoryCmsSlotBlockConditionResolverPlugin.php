<?php
/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Client\CmsSlotBlockCategoryConnector\Plugin\CmsSlotBlock;

use Generated\Shared\Transfer\CmsBlockTransfer;
use Spryker\Client\CmsSlotBlockExtension\Dependency\Plugin\CmsSlotBlockVisibilityResolverPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Client\CmsSlotBlockCategoryConnector\CmsSlotBlockCategoryConnectorFactory getFactory()
 */
class CategoryCmsSlotBlockConditionResolverPlugin extends AbstractPlugin implements CmsSlotBlockVisibilityResolverPluginInterface
{
    protected const CONDITION_KEY = 'category';
    protected const CMS_SLOT_DATA_CATEGORY_KEY = 'category';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $conditions
     *
     * @return bool
     */
    public function isApplicable(array $conditions): bool
    {
        return isset($conditions[static::CONDITION_KEY]);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     * @param array $conditions
     * @param array $cmsSlotData
     *
     * @return bool
     */
    public function isCmsBlockVisibleInSlot(
        CmsBlockTransfer $cmsBlockTransfer,
        array $conditions,
        array $cmsSlotData
    ): bool {
        return $this->getFactory()
            ->createCategoryCmsSlotBlockConditionResolver()
            ->isCmsBlockVisibleInSlot($cmsBlockTransfer, $conditions, $cmsSlotData);
    }
}
