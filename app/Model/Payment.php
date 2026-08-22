<?php

declare(strict_types=1);

namespace App\Model;

use Carbon\Carbon;
use Hyperf\DbConnection\Model\Model;

/**
 * Eloquent Model for payments table.
 *
 * This model serves as the data mapper between the database and the domain entity.
 * It should NOT contain business logic - only database mapping concerns.
 *
 * @property string $id Primary key (payment identifier)
 * @property int $amount Payment amount in cents
 * @property string $currency ISO 4217 currency code
 * @property string $description Payment description
 * @property string $status Payment status (pending, paid, failed, canceled)
 * @property Carbon $created_at Creation timestamp
 * @property Carbon $updated_at Update timestamp
 */
class Payment extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'payments';


    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public bool $incrementing = false;

    /**
     * The "type" of the auto-incrementing ID.
     */
    protected string $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     * @var list<string>
     */
    protected array $fillable = [
        'id',
        'amount',
        'currency',
        'description',
        'status',
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array<string, mixed>
     */
    protected array $casts = [
        'amount' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}
