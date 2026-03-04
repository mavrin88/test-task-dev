<?php

namespace TestTask\Infrastructure\SecondDataBase;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Столбцы таблицы OrderProgress
 * @property string $id
 * @property string $order_id
 * @property int $product_quantity
 * @property string $created_at
 */
final class EloquentOrderProgressModel extends Model
{
    public $incrementing = false;
    protected $connection = 'SecondDataBaseConnection';
    protected $table = 'OrderProgress';
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

    // TODO Ваш код...
}
