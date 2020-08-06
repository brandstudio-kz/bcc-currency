<?php

namespace BrandStudio\Currency\Http\Controllers;

use App\Http\Requests\PostRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;


class CurrencyCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ReorderOperation;

    public function setup()
    {
        CRUD::setModel(config('currency.currency_class'));
        CRUD::setRoute(config('backpack.base.route_prefix') . '/currency');
        CRUD::setEntityNameStrings(trans_choice('brandstudio::currency.currencies', 1), trans_choice('brandstudio::currency.currencies', 2));
        CRUD::orderBy('status', 'desc')->orderBy('lft');

        if (!config('currency.enable_create')) {
            CRUD::denyAccess('create');
            CRUD::denyAccess('delete');
        }
    }

    protected function setupListOperation()
    {
        CRUD::addButton('line', 'delete', 'view', 'brandstudio::currency.custom_delete_button');
        CRUD::addColumns([
            [
                'name' => 'angle',
                'label' => '#',
                'limit' => 99999,
                'escaped' => false,
                'orderable'  => true,
                'orderLogic' => function ($query, $column, $columnDirection) {
                     return $query->orderBy('index', $columnDirection);
                 }
            ],
            [
                'name' => 'name',
                'label' => trans('brandstudio::currency.name'),
            ],
            [
                'name' => 'title',
                'label' => trans('brandstudio::currency.title'),
            ],
            [
                'name' => 'value',
                'label' => trans('brandstudio::currency.value'),
                'type' => 'number',
                'decimals' => 2,
                'thousands_sep' => ' ',
                'suffix' => ' ₸',
            ],
            [
                'name' => 'change',
                'label' => trans('brandstudio::currency.description'),
                'limit' => 99999,
                'type' => 'text',
                'wrapper' => [
                    'element' => 'span',
                    'style' => function ($crud, $column, $entry, $related_key) {
                        return "color: {$entry->color}";
                    },
                ],
            ],
            [
                'name' => 'status',
                'label' => trans('brandstudio::currency.status'),
                'type' => 'check',
            ],
            [
                'name' => 'pub_date',
                'label' => trans('brandstudio::currency.pub_date'),
                'type' => 'date',
            ],
            [
                'name' => 'updated_at',
                'label' => trans('brandstudio::currency.updated_at'),
                'type' => 'datetime',
            ],
            [
                'name' => 'created_at',
                'label' => trans('brandstudio::currency.created_at'),
            ],
        ]);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(PostRequest::class);

        CRUD::addFields([
            [
                'name' => 'name',
                'label' => trans('brandstudio::currency.name'),
                'attributes' => [
                    'required' => true,
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-sm-6 required',
                ],
            ],
            [
                'name' => 'status',
                'label' => trans('brandstudio::currency.status'),
                'type' => 'select2_from_array',
                'options' => config('currency.currency_class')::getStatusOptions(),
                'wrapperAttributes' => [
                    'class' => 'from-group col-sm-6'
                ],
            ],
            [
                'name' => 'value',
                'label' => trans('brandstudio::currency.value'),
                'type' => 'number',
                'suffix' => ' ₸',
                'attributes' => [
                    'required' => true,
                    'step' => 0.01,
                ],
                'wrapperAttributes' => [
                    'class' => 'form-group col-sm-12 required'
                ],
            ],
        ]);
    }

    protected function setupUpdateOperation()
    {
        $entry = $this->crud->getEntry(request()->id);
        $this->setupCreateOperation();
        if ($entry->pub_date) {
            CRUD::modifyField('value', [
                'attributes' => [
                    'required' => true,
                    'step' => 0.01,
                    'disabled' => !config('currency.enable_update'),
                    'readonly' => config('currency.enable_update'),
                ],
            ]);
        }
    }

    protected function setupReorderOperation()
    {
        CRUD::set('reorder.label', 'name');
        CRUD::set('reorder.max_level', 1);
    }
}
