<?php

namespace TestTask\Infrastructure\FirstDataBase;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use TestTask\Infrastructure\SecondDataBase\EloquentOrderProgressModel;

/**
 * Столбцы таблицы Order
 * @property string $id
 * @property string $title
 * @property string $product_id
 * @property int $product_quantity
 * @property string $priority
 * @property string|null $deadline_at
 * @property string $created_at
 */
final class EloquentOrderModel extends Model
{
    public $incrementing = false;
    protected $connection = 'FirstDataBaseConnection';
    protected $table = 'Order';
    protected $primaryKey = 'id';
    protected $keyType = 'string';

    public static function getById(string $id, bool $lock): ?self
    {
        $model = self::query($lock)->where('id', '=', $id)->first();

        return ($model instanceof self) ? $model : null;
    }

    public static function query(bool $lock = false): Builder
    {
        return $lock ? parent::query()->lockForUpdate() : parent::query();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(EloquentProductModel::class, 'product_id', 'id');
    }

    public function progresses(): HasMany
    {
        return $this->hasMany(EloquentOrderProgressModel::class, 'order_id', 'id');
    }
}
