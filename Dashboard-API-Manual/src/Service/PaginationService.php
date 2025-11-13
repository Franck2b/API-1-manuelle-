<?php

namespace App\Service;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

class PaginationService
{
    public function paginate(QueryBuilder $queryBuilder, Request $request, int $defaultLimit = 10): array
    {
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = max(1, min(100, (int) $request->query->get('limit', $defaultLimit)));
        $offset = ($page - 1) * $limit;

        // Get total count before pagination
        $totalCount = count($queryBuilder->getQuery()->getResult());

        // Apply pagination
        $queryBuilder
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $items = $queryBuilder->getQuery()->getResult();
        $totalPages = (int) ceil($totalCount / $limit);

        return [
            'items' => $items,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $totalCount,
                'totalPages' => $totalPages,
                'hasNext' => $page < $totalPages,
                'hasPrev' => $page > 1,
            ],
        ];
    }

    public function applyFilters(QueryBuilder $queryBuilder, Request $request, array $allowedFilters): void
    {
        foreach ($allowedFilters as $filter => $field) {
            $value = $request->query->get($filter);
            if ($value !== null && $value !== '') {
                if (is_array($field)) {
                    // Handle multiple fields (OR condition)
                    $orX = $queryBuilder->expr()->orX();
                    foreach ($field as $f) {
                        $orX->add($queryBuilder->expr()->like("e.{$f}", ":{$filter}"));
                    }
                    $queryBuilder->andWhere($orX)
                        ->setParameter($filter, "%{$value}%");
                } else {
                    $queryBuilder->andWhere("e.{$field} = :{$filter}")
                        ->setParameter($filter, $value);
                }
            }
        }
    }

    public function applySorting(QueryBuilder $queryBuilder, Request $request, array $allowedSorts, string $defaultSort = 'id', string $defaultOrder = 'ASC'): void
    {
        $sort = $request->query->get('sort', $defaultSort);
        $order = strtoupper($request->query->get('order', $defaultOrder));

        if (!in_array($sort, $allowedSorts)) {
            $sort = $defaultSort;
        }

        if (!in_array($order, ['ASC', 'DESC'])) {
            $order = $defaultOrder;
        }

        $queryBuilder->orderBy("e.{$sort}", $order);
    }
}

