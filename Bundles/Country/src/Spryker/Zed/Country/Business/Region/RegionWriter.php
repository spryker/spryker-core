<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business\Region;

use Generated\Shared\Transfer\RegionTransfer;
use Spryker\Zed\Country\Business\Exception\RegionExistsException;
use Spryker\Zed\Country\Persistence\CountryEntityManagerInterface;

class RegionWriter implements RegionWriterInterface
{
    /**
     * @var \Spryker\Zed\Country\Persistence\CountryEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var \Spryker\Zed\Country\Business\Region\RegionReaderInterface
     */
    protected $regionReader;

    /**
     * @param \Spryker\Zed\Country\Persistence\CountryEntityManagerInterface $entityManager
     * @param \Spryker\Zed\Country\Business\Region\RegionReaderInterface $regionReader
     */
    public function __construct(
        CountryEntityManagerInterface $entityManager,
        RegionReaderInterface $regionReader
    ) {
        $this->entityManager = $entityManager;
        $this->regionReader = $regionReader;
    }

    /**
     * @param string $isoCode
     * @param int $fkCountry
     * @param string $regionName
     *
     * @return \Generated\Shared\Transfer\RegionTransfer
     */
    public function createRegion(string $isoCode, int $fkCountry, string $regionName): RegionTransfer
    {
        $this->assertRegionDoesNotExist($isoCode);

        $regionTransfer = (new RegionTransfer())->setName($regionName)
            ->setIso2Code($isoCode)
            ->setFkCountry($fkCountry);

        return $this->entityManager->createRegion($regionTransfer);
    }

    /**
     * @param string $isoCode
     *
     * @throws \Spryker\Zed\Country\Business\Exception\RegionExistsException
     *
     * @return void
     */
    protected function assertRegionDoesNotExist(string $isoCode): void
    {
        if ($this->regionReader->regionExists($isoCode)) {
            throw new RegionExistsException();
        }
    }
}
