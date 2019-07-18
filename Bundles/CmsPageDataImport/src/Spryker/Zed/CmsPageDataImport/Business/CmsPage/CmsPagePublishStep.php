<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsPageDataImport\Business\CmsPage;

use Spryker\Zed\Cms\Dependency\CmsEvents;
use Spryker\Zed\CmsPageDataImport\Business\DataSet\CmsPageStoreDataSet;
use Spryker\Zed\CmsPageDataImport\Dependency\Facade\CmsPageDataImportToCmsFacadeInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CmsPagePublishStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\CmsPageDataImport\Dependency\Facade\CmsPageDataImportToCmsFacadeInterface
     */
    protected $cmsFacade;

    /**
     * @param \Spryker\Zed\CmsPageDataImport\Dependency\Facade\CmsPageDataImportToCmsFacadeInterface $cmsFacade
     */
    public function __construct(CmsPageDataImportToCmsFacadeInterface $cmsFacade)
    {
        $this->cmsFacade = $cmsFacade;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $cmsVersionTransfer = $this->cmsFacade->publishWithVersion($dataSet[CmsPageStoreDataSet::ID_CMS_PAGE]);

        $this->addPublishEvents(CmsEvents::CMS_VERSION_PUBLISH, $cmsVersionTransfer->getFkCmsPage());
    }
}
