<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business\MerchantProfile;

use ArrayObject;
use Generated\Shared\Transfer\MerchantProfileAddressCollectionTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\MerchantProfile\Business\MerchantProfileAddress\MerchantProfileAddressWriterInterface;
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
     * @var \Spryker\Zed\MerchantProfile\Business\MerchantProfileAddress\MerchantProfileAddressWriterInterface
     */
    protected $merchantProfileAddressWriter;

    /**
     * @param \Spryker\Zed\MerchantProfile\Persistence\MerchantProfileEntityManagerInterface $merchantProfileEntityManager
     * @param \Spryker\Zed\MerchantProfile\Business\MerchantProfileGlossary\MerchantProfileGlossaryWriterInterface $merchantProfileGlossaryWriter
     * @param \Spryker\Zed\MerchantProfile\Dependency\Facade\MerchantProfileToUrlFacadeInterface $urlFacade
     * @param \Spryker\Zed\MerchantProfile\Business\MerchantProfileAddress\MerchantProfileAddressWriterInterface $merchantProfileAddressWriter
     */
    public function __construct(
        MerchantProfileEntityManagerInterface $merchantProfileEntityManager,
        MerchantProfileGlossaryWriterInterface $merchantProfileGlossaryWriter,
        MerchantProfileToUrlFacadeInterface $urlFacade,
        MerchantProfileAddressWriterInterface $merchantProfileAddressWriter
    ) {
        $this->merchantProfileEntityManager = $merchantProfileEntityManager;
        $this->merchantProfileGlossaryWriter = $merchantProfileGlossaryWriter;
        $this->urlFacade = $urlFacade;
        $this->merchantProfileAddressWriter = $merchantProfileAddressWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function create(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer
    {
        $merchantProfileAddressCollectionTransfer = $merchantProfileTransfer->getAddressCollection();
        $merchantProfileTransfer = $this->merchantProfileGlossaryWriter->saveMerchantProfileGlossaryAttributes($merchantProfileTransfer);
        $merchantProfileTransfer = $this->merchantProfileEntityManager->create($merchantProfileTransfer);
        $merchantProfileTransfer = $this->saveMerchantProfileAddress($merchantProfileTransfer, $merchantProfileAddressCollectionTransfer);
        $merchantProfileTransfer = $this->saveMerchantProfileUrls($merchantProfileTransfer);

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function update(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer
    {
        $merchantProfileAddressCollectionTransfer = $merchantProfileTransfer->getAddressCollection();
        $merchantProfileTransfer = $this->merchantProfileGlossaryWriter->saveMerchantProfileGlossaryAttributes($merchantProfileTransfer);
        $merchantProfileTransfer = $this->merchantProfileEntityManager->update($merchantProfileTransfer);
        $merchantProfileTransfer = $this->saveMerchantProfileAddress($merchantProfileTransfer, $merchantProfileAddressCollectionTransfer);
        $merchantProfileTransfer = $this->saveMerchantProfileUrls($merchantProfileTransfer);

        return $merchantProfileTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     * @param \Generated\Shared\Transfer\MerchantProfileAddressCollectionTransfer|null $merchantProfileAddressCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    protected function saveMerchantProfileAddress(
        MerchantProfileTransfer $merchantProfileTransfer,
        ?MerchantProfileAddressCollectionTransfer $merchantProfileAddressCollectionTransfer
    ): MerchantProfileTransfer {
        if ($merchantProfileAddressCollectionTransfer === null) {
            return $merchantProfileTransfer;
        }
        $merchantProfileAddressCollectionTransfer = $this->merchantProfileAddressWriter->saveMerchantProfileAddressCollection(
            $merchantProfileAddressCollectionTransfer,
            $merchantProfileTransfer->getIdMerchantProfile()
        );
        $merchantProfileTransfer->setAddressCollection($merchantProfileAddressCollectionTransfer);

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
