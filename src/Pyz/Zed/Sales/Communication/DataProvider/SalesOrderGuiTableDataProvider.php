<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\Sales\Communication\DataProvider;

use Generated\Shared\Transfer\GuiTableDataRequestTransfer;
use Generated\Shared\Transfer\GuiTableDataResponseTransfer;
use Generated\Shared\Transfer\GuiTableRowDataResponseTransfer;
use Generated\Shared\Transfer\SalesOrderTableCriteriaTransfer;
use Pyz\Zed\Sales\Communication\ConfigurationProvider\SalesOrderTableConfigurationProvider;
use Pyz\Zed\Sales\Persistence\SalesRepository;
use Spryker\Shared\GuiTable\DataProvider\AbstractGuiTableDataProvider;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class SalesOrderGuiTableDataProvider extends AbstractGuiTableDataProvider
{
    public function __construct(
        protected SalesRepository $salesRepository,
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function createCriteria(GuiTableDataRequestTransfer $guiTableDataRequestTransfer): AbstractTransfer
    {
        return (new SalesOrderTableCriteriaTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderTableCriteriaTransfer $criteriaTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer
     */
    protected function fetchData(AbstractTransfer $criteriaTransfer): GuiTableDataResponseTransfer
    {
        $salesOrderCollectionTransfer = $this->salesRepository->getSalesOrderTableData($criteriaTransfer);
        $guiTableDataResponseTransfer = new GuiTableDataResponseTransfer();
        /** @var \Generated\Shared\Transfer\LocaleTransfer $localeTransfer */

        foreach ($salesOrderCollectionTransfer->getOrders() as $orderTransfer) {
            $responseData = [
                SalesOrderTableConfigurationProvider::COL_KEY_ORDER_REFERENCE => $orderTransfer->getOrderReference(),
                SalesOrderTableConfigurationProvider::COL_KEY_FIRST_NAME => $orderTransfer->getFirstName(),
                SalesOrderTableConfigurationProvider::COL_KEY_LAST_NAME => $orderTransfer->getLastName(),
                SalesOrderTableConfigurationProvider::COL_KEY_CREATED_AT => $orderTransfer->getCreatedAt(),
            ];

            $guiTableDataResponseTransfer->addRow((new GuiTableRowDataResponseTransfer())->setResponseData($responseData));
        }

        /** @var \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer */
        $paginationTransfer = $salesOrderCollectionTransfer->requirePagination()->getPagination();
        $guiTableDataResponseTransfer
            ->setPage($paginationTransfer->requirePage()->getPage())
            ->setPageSize($paginationTransfer->requireMaxPerPage()->getMaxPerPage())
            ->setTotal($paginationTransfer->requireNbResults()->getNbResults());

        return $guiTableDataResponseTransfer;
    }
}
