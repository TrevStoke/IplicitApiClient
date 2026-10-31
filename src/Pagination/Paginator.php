<?php

declare(strict_types=1);

namespace Iplicit\Pagination;

/**
 * Paginator for iterating through paginated Iplicit API results.
 * Implements IteratorAggregate to allow seamless foreach iteration over all items
 * across multiple API pages using generators (yield).
 *
 * @template T
 * @implements \IteratorAggregate<int, T>
 */
class Paginator implements \IteratorAggregate
{
    /** @var callable(int, int): array<T> */
    private $fetchPageCallback;
    private int $pageSize;
    private int $initialSkip;
    private ?int $maxItems;

    /**
     * @param callable(int $skip, int $take): array<T> $fetchPageCallback Callback that takes ($skip, $take) and returns an array of items
     * @param int $pageSize Default page size (take)
     * @param int $initialSkip Starting offset (skip)
     * @param int|null $maxItems Maximum total items to retrieve (null for unlimited)
     */
    public function __construct(
        callable $fetchPageCallback,
        int $pageSize = 50,
        int $initialSkip = 0,
        ?int $maxItems = null
    ) {
        $this->fetchPageCallback = $fetchPageCallback;
        $this->pageSize = max(1, $pageSize);
        $this->initialSkip = max(0, $initialSkip);
        $this->maxItems = $maxItems;
    }

    /**
     * Iterates over all items across all pages, yielding each item individually.
     *
     * @return \Generator<int, T>
     */
    public function getIterator(): \Generator
    {
        $yieldedCount = 0;
        $skip = $this->initialSkip;

        while (true) {
            $take = $this->pageSize;
            if ($this->maxItems !== null) {
                $remaining = $this->maxItems - $yieldedCount;
                if ($remaining <= 0) {
                    break;
                }
                $take = min($take, $remaining);
            }

            $callback = $this->fetchPageCallback;
            $items = $callback($skip, $take);

            if (!is_array($items) || empty($items)) {
                break;
            }

            $batchCount = 0;
            foreach ($items as $item) {
                yield $item;
                $yieldedCount++;
                $batchCount++;

                if ($this->maxItems !== null && $yieldedCount >= $this->maxItems) {
                    return;
                }
            }

            // If the returned batch was smaller than the requested page size, we reached the end
            if ($batchCount < $take) {
                break;
            }

            $skip += $batchCount;
        }
    }

    /**
     * Iterates page by page, yielding each page array.
     *
     * @return \Generator<int, array<T>>
     */
    public function iteratePages(): \Generator
    {
        $yieldedCount = 0;
        $skip = $this->initialSkip;

        while (true) {
            $take = $this->pageSize;
            if ($this->maxItems !== null) {
                $remaining = $this->maxItems - $yieldedCount;
                if ($remaining <= 0) {
                    break;
                }
                $take = min($take, $remaining);
            }

            $callback = $this->fetchPageCallback;
            $items = $callback($skip, $take);

            if (!is_array($items) || empty($items)) {
                break;
            }

            yield $items;

            $batchCount = count($items);
            $yieldedCount += $batchCount;

            if ($this->maxItems !== null && $yieldedCount >= $this->maxItems) {
                return;
            }

            if ($batchCount < $take) {
                break;
            }

            $skip += $batchCount;
        }
    }

    /**
     * Fetches all items into a single flat array.
     *
     * @return array<T>
     */
    public function fetchAll(): array
    {
        $results = [];
        foreach ($this->getIterator() as $item) {
            $results[] = $item;
        }
        return $results;
    }

    /**
     * Sets the maximum number of items to retrieve.
     */
    public function limit(int $max): self
    {
        $this->maxItems = max(0, $max);
        return $this;
    }

    /**
     * Sets the page size for subsequent page requests.
     */
    public function pageSize(int $size): self
    {
        $this->pageSize = max(1, $size);
        return $this;
    }
}
