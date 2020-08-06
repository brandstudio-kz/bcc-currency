<?php
namespace BrandStudio\Currency;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Currency extends Model
{
    use CrudTrait;

    const DRAFT     = 0;
    const PUBLISHED = 1;

    protected $table = 'currencies';
    protected $guarded = ['id'];
    protected $dates = ['pub_date'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($currency) {
            if (!$currency->title) {
                $currency->title = mb_strtolower($currency->name);
            } else if (!$currency->name) {
                $currency->name = mb_strtoupper($currency->title);
            }
        });

        static::updating(function($currency) {
            if (is_null($currency->value)) {
                $currency->status = static::DRAFT;
            }
        });
    }

    // Scopes
    public function scopeDraft($query)
    {
        $query->where('status', static::DRAFT);
    }

    public function scopePublished($query)
    {
        $query->where('status', static::PUBLISHED);
    }

    // Accessors
    public static function getStatusOptions() : array
    {
        return [
            static::DRAFT => trans('brandstudio::currency.draft'),
            static::PUBLISHED => trans('brandstudio::currency.published'),
        ];
    }

    public function getAngleAttribute() : string
    {
        return $this->index == 'DOWN' ? "<i class='las la-angle-down' style='color: red;'></i>" : ($this->index == 'UP' ? ("<i class='las la-angle-up' style='color: green;'></i>") : '');
    }

    public function getColorAttribute() : string
    {
        return $this->index == 'DOWN' ? "red" : ($this->index == 'UP' ? "green" : 'grey');
    }
}
