<?php

namespace Pyz\Zed\Sales\Persistence;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SalesOrderCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderTableCriteriaTransfer;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\Util\PropelModelPager;
use Spryker\Zed\Sales\Persistence\SalesRepository as SprykerSalesRepository;

/**
 * @method \Pyz\Zed\Sales\Persistence\SalesPersistenceFactory getFactory()
 */
class SalesRepository extends SprykerSalesRepository
{

    /**
     * @param \Generated\Shared\Transfer\SalesOrderTableCriteriaTransfer $salesOrderTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderCollectionTransfer
     */
    public function getSalesOrderTableData(SalesOrderTableCriteriaTransfer $salesOrderTableCriteriaTransfer): SalesOrderCollectionTransfer
    {
        $salesOrderQuery = $this->buildSalesOrderTableBaseQuery($salesOrderTableCriteriaTransfer);
        $salesOrderQuery = $this->applySearch($salesOrderQuery, $salesOrderTableCriteriaTransfer);
//        $salesOrderQuery = $this->addProductFilters($salesOrderQuery, $productTableCriteriaTransfer);
        $salesOrderQuery = $this->addSorting($salesOrderQuery, $salesOrderTableCriteriaTransfer);

        $propelPager = $salesOrderQuery->paginate(
            $salesOrderTableCriteriaTransfer->requirePage()->getPage(),
            $salesOrderTableCriteriaTransfer->requirePageSize()->getPageSize(),
        );

        $paginationTransfer = $this->hydratePaginationTransfer($propelPager);

        $productConcreteMapper = $this->getFactory()->createSalesOrderTableDataMapper();
        $productConcreteCollectionTransfer = $productConcreteMapper->mapSalesOrderTableDataArrayToSalesOrderCollectionTransfer(
            $propelPager->getResults()->getData(),
            new SalesOrderCollectionTransfer(),
        );
        $productConcreteCollectionTransfer->setPagination($paginationTransfer);

        return $productConcreteCollectionTransfer;
    }

    /**
     * @module Product
     * @module ProductOffer
     * @module ProductImage
     * @module ProductValidity
     *
     * @param \Generated\Shared\Transfer\SalesOrderTableCriteriaTransfer $salesOrderTableCriteriaTransfer
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderQuery
     */
    protected function buildSalesOrderTableBaseQuery(SalesOrderTableCriteriaTransfer $salesOrderTableCriteriaTransfer): SpySalesOrderQuery
    {
        $salesOrderQuery = SpySalesOrderQuery::create();

        $salesOrderQuery->clearSelectColumns()
            ->addAsColumn(OrderTransfer::ID_SALES_ORDER, SpySalesOrderTableMap::COL_ID_SALES_ORDER)
            ->addAsColumn(OrderTransfer::ORDER_REFERENCE, SpySalesOrderTableMap::COL_ORDER_REFERENCE)
            ->addAsColumn(OrderTransfer::FIRST_NAME, SpySalesOrderTableMap::COL_FIRST_NAME)
            ->addAsColumn(OrderTransfer::LAST_NAME, SpySalesOrderTableMap::COL_LAST_NAME)
            ->addAsColumn(OrderTransfer::CREATED_AT, SpySalesOrderTableMap::COL_CREATED_AT)
            ->setFormatter(ModelCriteria::FORMAT_ARRAY);

        return $salesOrderQuery;
    }


    /**
     * @param \Propel\Runtime\Util\PropelModelPager $propelPager
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function hydratePaginationTransfer(
        PropelModelPager $propelPager
    ): PaginationTransfer {
        return (new PaginationTransfer())
            ->setNbResults($propelPager->getNbResults())
            ->setPage($propelPager->getPage())
            ->setMaxPerPage($propelPager->getMaxPerPage())
            ->setFirstIndex($propelPager->getFirstIndex())
            ->setLastIndex($propelPager->getLastIndex())
            ->setFirstPage($propelPager->getFirstPage())
            ->setLastPage($propelPager->getLastPage())
            ->setNextPage($propelPager->getNextPage())
            ->setPreviousPage($propelPager->getPreviousPage());
    }

    protected function addSorting(SpySalesOrderQuery $salesOrderQuery, SalesOrderTableCriteriaTransfer $salesOrderTableCriteriaTransfer): SpySalesOrderQuery
    {
        if ($salesOrderTableCriteriaTransfer->getOrderBy()) {
            $salesOrderQuery->orderBy($salesOrderTableCriteriaTransfer->getOrderBy(), $salesOrderTableCriteriaTransfer->getOrderDirection());
        }

        return $salesOrderQuery;
    }

    protected function applySearch(SpySalesOrderQuery $salesOrderQuery, SalesOrderTableCriteriaTransfer $salesOrderTableCriteriaTransfer): SpySalesOrderQuery
    {
        if ($salesOrderTableCriteriaTransfer->getSearchTerm()) {
            $salesOrderQuery->filterByOrderReference('%'.$salesOrderTableCriteriaTransfer->getSearchTerm().'%', Criteria::LIKE);
        }

        return $salesOrderQuery;
    }
}
