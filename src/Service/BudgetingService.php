<?php

declare(strict_types=1);

namespace Iplicit\Service;

use Iplicit\Pagination\Paginator;

/**
 * Service for Iplicit Budgeting API operations.
 */
class BudgetingService extends AbstractService
{
    /**
     * Get a list of budgets with a search filter
     *
     * @param array $query Query parameters
     * @return mixed
     */
    public function getBudgetings(array $query = []): mixed
    {
        $path = '/api/Budgeting';
        return $this->get($path, $query);
    }

    /**
     * Returns a Paginator instance for Get a list of budgets with a search filter.
     */
    public function paginateBudgetings(array $query = [], int $pageSize = 50): Paginator
    {
        $path = '/api/Budgeting';
        return $this->paginate($path, $query, 'skip', 'take', $pageSize);
    }

    /**
     * Yields all items for Get a list of budgets with a search filter across pages using a generator.
     *
     * @return \Generator
     */
    public function fetchAllBudgetings(array $query = [], int $pageSize = 50): \Generator
    {
        $paginator = $this->paginateBudgetings($query, $pageSize);
        yield from $paginator;
    }

    /**
     * GetBudgeting
     *
     * @param string|int $budgetingRef
     * @param array $query Query parameters
     * @return mixed
     */
    public function getBudgeting(string|int $budgetingRef, array $query = []): mixed
    {
        $path = '/api/Budgeting/' . rawurlencode((string)$budgetingRef);
        return $this->get($path, $query);
    }

    /**
     * Get budget amounts for a budget
     *
     * @param string|int $budgetingId
     * @param array $query Query parameters
     * @return mixed
     */
    public function getBudgetingAmounts(string|int $budgetingId, array $query = []): mixed
    {
        $path = '/api/Budgeting/' . rawurlencode((string)$budgetingId) . '/amounts';
        return $this->get($path, $query);
    }

    /**
     * Returns a Paginator instance for Get budget amounts for a budget.
     */
    public function paginateBudgetingAmounts(string|int $budgetingId, array $query = [], int $pageSize = 50): Paginator
    {
        $path = '/api/Budgeting/' . rawurlencode((string)$budgetingId) . '/amounts';
        return $this->paginate($path, $query, 'skip', 'take', $pageSize);
    }

    /**
     * Yields all items for Get budget amounts for a budget across pages using a generator.
     *
     * @return \Generator
     */
    public function fetchAllBudgetingAmounts(string|int $budgetingId, array $query = [], int $pageSize = 50): \Generator
    {
        $paginator = $this->paginateBudgetingAmounts($budgetingId, $query, $pageSize);
        yield from $paginator;
    }

}
