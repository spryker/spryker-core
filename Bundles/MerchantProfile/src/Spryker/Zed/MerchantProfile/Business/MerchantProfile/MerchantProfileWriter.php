<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business\MerchantProfile;

use ArrayObject;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\MerchantProfile\Business\MerchantProfileGlossary\MerchantProfileGlossaryWriterInterface;
use Spryker\Zed\MerchantProfile\Dependency\Facade\MerchantProfileToUrlFacadeInterface;
use Spryker\Zed\MerchantProfile\Persistence\MerchantProfileEntityManagerInterface;

class MerchantProfileWriter implements MerchantProfileWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantProfile\Persistence\MerchantProfileEntityManagerInterface
     */
    protected $merchantProfileEntityManager;

    /**
     * @var \Spryker\Zed\MerchantProfile\Business\MerchantProfileGlossary\MerchantProfileGlossaryWriterInterface
     */
    protected $merchantProfileGlossaryWriter;

    /**
     * @var \Spryker\Zed\MerchantProfile\Dependency\Facade\MerchantProfileToUrlFacadeInterface
     */
    protected $urlFacade;

    /**
     * @param \Spryker\Zed\MerchantProfile\Persistence\MerchantProfileEntityManagerInterface $merchantProfileEntityManager
     * @param \Spryker\Zed\MerchantProfile\Business\MerchantProfileGlossary\MerchantProfileGlossaryWriterInterface $merchantProfileGlossaryWriter
     * @param \Spryker\Zed\MerchantProfile\Dependency\Facade\MerchantProfileToUrlFacadeInterface $urlFacade
     */
    public function __construct(
        MerchantProfileEntityManagerInterface $merchantProfileEntityManager,
        MerchantProfileGlossaryWriterInterface $merchantProfileGlossaryWriter,
        MerchantProfileToUrlFacadeInterface $urlFacade
    ) {
        $this->merchantProfileEntityManager = $merchantProfileEntityManager;
        $this->merchantProfileGlossaryWriter = $merchantProfileGlossaryWriter;
        $this->urlFacade = $urlFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function saveMerchantProfile(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer
    {
        $merchantProfileTransfer = $this->merchantProfileGlossaryWriter->saveMerchantProfileGlossaryAttributes($merchantProfileTransfer);
        $merchantProfileTransfer = $this->merchantProfileEntityManager->saveMerchantProfile($merchantProfileTransfer);
        $merchantProfileTransfer = $this->saveMerchantProfileUrls($merchantProfileTransfer);

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer[
     */
    protected function saveMerchantProfileUrls(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer
    {
        $merchantProfileUrlTransfers = $merchantProfileTransfer->getUrlCollection();
        $idMerchantProfile = $merchantProfileTransfer->getIdMerchantProfile();

        $urlTransferCollection = new ArrayObject();
        foreach ($merchantProfileUrlTransfers as $merchantProfileUrlTransfer) {
            $urlTransfer = new UrlTransfer();
            $urlTransfer->fromArray($merchantProfileUrlTransfer->toArray(), false);
            $urlTransfer->setFkResourceMerchantProfile($idMerchantProfile);

            $urlTransfer = $this->saveMerchantProfileUrl($urlTransfer);
            $urlTransferCollection->append($urlTransfer);
        }

        $merchantProfileTransfer->setUrlCollection($urlTransferCollection);

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Generated\Shared\Transfer\UrlTransfer
     */
    protected function saveMerchantProfileUrl(UrlTransfer $urlTransfer): UrlTransfer
    {
        if ($urlTransfer->getIdUrl() === null) {
            return $this->urlFacade->createUrl($urlTransfer);
        }

        return $this->urlFacade->updateUrl($urlTransfer);
    }
}
