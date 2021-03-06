<?php

namespace AlephTools\DDD\Common\Infrastructure\SqlBuilder\Traits;

use AlephTools\DDD\Common\Infrastructure\SqlBuilder\Expressions\ReturningExpression;

trait ReturningAware
{
    /**
     * The RETURNING expression instance.
     *
     * @var ReturningExpression
     */
    private $returning;

    public function returning($column = null, $alias = null): self
    {
        $this->returning = $this->returning ?? new ReturningExpression();
        if ($column !== null) {
            $this->returning->append($column, $alias);
        }
        $this->built = false;
        return $this;
    }

    private function buildReturning(): void
    {
        if ($this->returning) {
            $this->sql .= ' RETURNING ' . ($this->returning->toSql() ?: '*');
            $this->addParams($this->returning->getParams());
        }
    }
}
