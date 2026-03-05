<?php

namespace TestTask\Infrastructure\FirstDataBase;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

/**
 * Столбцы таблицы Product
 * @property string $id
 * @property string $name
 */
final class EloquentProductModel extends Model
{
    public $incrementing = false;
    protected $connection = 'FirstDataBaseConnection';
    protected $table = 'Product';
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

    public function orders(): HasMany
    {
        return $this->hasMany(EloquentOrderModel::class, 'product_id', 'id');
    }
}
