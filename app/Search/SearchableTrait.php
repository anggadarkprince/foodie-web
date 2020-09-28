<?php

namespace App\Search;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait SearchableTrait
{
    protected $baseModel;
    protected $decoratorNamespace;

    /**
     * Set base model.
     *
     * @param $baseModel
     * @return $this
     */
    public function withBaseModel($baseModel)
    {
        $this->baseModel = $baseModel;

        return $this;
    }

    /**
     * Get base model.
     *
     * @return mixed
     */
    public function getBaseModel()
    {
        return $this->baseModel;
    }

    /**
     * Get decorator namespace.
     *
     * @return mixed
     */
    public function getDecoratorNamespace()
    {
        return $this->decoratorNamespace;
    }

    /**
     * @param Request $filters
     * @param null $baseModel
     * @return Builder[]|Collection
     */
    public function apply(Request $filters, $baseModel = null)
    {
        return $this->getResult(
            $this->applyDecoratorsFromRequest($filters, $baseModel ?: $this->getBaseModel())
        );
    }

    /**
     * Apply decorator from request.
     *
     * @param Request $request
     * @param Builder $query
     * @return Builder
     */
    private function applyDecoratorsFromRequest(Request $request, Builder $query)
    {
        foreach ($request->all() as $filterName => $value) {

            $decorator = $this->createFilterDecorator($filterName);

            if ($this->isValidDecorator($decorator)) {
                $query = $decorator::apply($query, $value);
            }

        }
        return $query;
    }

    /**
     * Create filter decorator name.
     *
     * @param $name
     * @return string
     */
    private function createFilterDecorator($name)
    {
        return $this->getDecoratorNamespace() . Str::studly($name);
    }

    /**
     * Check if decorator is valid file class.
     *
     * @param $decorator
     * @return bool
     */
    private function isValidDecorator($decorator)
    {
        return class_exists($decorator);
    }

    /**
     * @param Builder $builder
     * @return Builder[]|Collection
     */
    protected function getResult(Builder $builder)
    {
        return $builder->get();
    }
}
