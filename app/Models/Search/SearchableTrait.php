<?php

namespace App\Models\Search;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait SearchableTrait
{
    /**
     * The base query builder instance.
     *
     * @var Builder
     */
    protected $baseModel;

    /**
     * Decorator base namespace.
     *
     * @var string
     */
    protected $decoratorNamespace = self::class . '\\Filters\\';

    /**
     * Set base model.
     *
     * @param $baseModel
     * @return $this
     */
    public function withBaseModel(Builder $baseModel)
    {
        $this->baseModel = $baseModel;

        return $this;
    }

    /**
     * Get condition if decorator only will be applied,
     * if the value is empty or not.
     *
     * @return bool
     */
    public function applyOnlyIfFilled()
    {
        return false;
    }

    /**
     * Get base model.
     *
     * @param Request $filters
     * @return Builder
     */
    public function getBaseModel(Request $filters)
    {
        return $this->baseModel;
    }

    /**
     * Get decorator namespace.
     *
     * @return string
     */
    public function getDecoratorNamespace()
    {
        return substr(get_class(), 0, strrpos(get_class(), '\\')) . '\\Filters\\';
    }

    /**
     * @param Request $filters
     * @param null $baseModel
     * @return Builder[]|Collection
     */
    public function apply(Request $filters, $baseModel = null)
    {
        return $this->getResult(
            $this->applyDecoratorsFromRequest($filters, $baseModel ?: $this->getBaseModel($filters))
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

            $willBeApplied = true;
            if ($this->applyOnlyIfFilled()) {
                $willBeApplied = $request->filled($filterName);
            }

            if ($this->isValidDecorator($decorator) && $willBeApplied) {
                $query = $decorator::apply($query, $value, $request);
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
