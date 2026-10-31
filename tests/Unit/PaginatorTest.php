<?php

declare(strict_types=1);

namespace Iplicit\Tests\Unit;

use Iplicit\Pagination\Paginator;
use PHPUnit\Framework\TestCase;

class PaginatorTest extends TestCase
{
    public function testIteratesAllPagesUsingGenerator(): void
    {
        $allData = range(1, 105); // 105 items
        $requests = [];

        $paginator = new Paginator(
            function (int $skip, int $take) use ($allData, &$requests): array {
                $requests[] = ['skip' => $skip, 'take' => $take];
                return array_slice($allData, $skip, $take);
            },
            pageSize: 50
        );

        $yielded = [];
        foreach ($paginator as $item) {
            $yielded[] = $item;
        }

        $this->assertCount(105, $yielded);
        $this->assertSame($allData, $yielded);

        // Check requests made:
        // Page 1: skip 0, take 50 (returns 50)
        // Page 2: skip 50, take 50 (returns 50)
        // Page 3: skip 100, take 50 (returns 5 -> terminated)
        $this->assertCount(3, $requests);
        $this->assertSame(['skip' => 0, 'take' => 50], $requests[0]);
        $this->assertSame(['skip' => 50, 'take' => 50], $requests[1]);
        $this->assertSame(['skip' => 100, 'take' => 50], $requests[2]);
    }

    public function testFetchAll(): void
    {
        $allData = ['item1', 'item2', 'item3'];

        $paginator = new Paginator(
            fn(int $skip, int $take) => array_slice($allData, $skip, $take),
            pageSize: 2
        );

        $results = $paginator->fetchAll();
        $this->assertSame($allData, $results);
    }

    public function testIteratePages(): void
    {
        $allData = range(1, 15);

        $paginator = new Paginator(
            fn(int $skip, int $take) => array_slice($allData, $skip, $take),
            pageSize: 5
        );

        $pages = [];
        foreach ($paginator->iteratePages() as $page) {
            $pages[] = $page;
        }

        $this->assertCount(3, $pages);
        $this->assertSame([1, 2, 3, 4, 5], $pages[0]);
        $this->assertSame([6, 7, 8, 9, 10], $pages[1]);
        $this->assertSame([11, 12, 13, 14, 15], $pages[2]);
    }

    public function testLimitConstraint(): void
    {
        $allData = range(1, 100);

        $paginator = new Paginator(
            fn(int $skip, int $take) => array_slice($allData, $skip, $take),
            pageSize: 10
        );
        $paginator->limit(25);

        $results = $paginator->fetchAll();
        $this->assertCount(25, $results);
        $this->assertSame(range(1, 25), $results);
    }

    public function testEmptyResultImmediatelyTerminates(): void
    {
        $calls = 0;
        $paginator = new Paginator(
            function (int $skip, int $take) use (&$calls): array {
                $calls++;
                return [];
            },
            pageSize: 50
        );

        $results = $paginator->fetchAll();
        $this->assertEmpty($results);
        $this->assertSame(1, $calls);
    }
}
