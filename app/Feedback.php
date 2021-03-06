<?php

namespace App;

use App\traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Redis;

class Feedback extends Model
{
    use Searchable;

    /**
     * @var string
     */
    protected $table = 'feedback';

    /**
     * @var array
     */
    protected $fillable = ['company_token', 'priority'];

    protected static function boot()
    {
        self::creating(function ($feedback) {
            $feedback->number = self::getCompanyLastNumber($feedback->company_token) + 1;
        });

        parent::boot(); // TODO: Change the autogenerated stub
    }

    /**
     * @return HasOne
     */
    public function state(): HasOne
    {
        return $this->hasOne(State::class);
    }

    /**
     * Generate company last number.
     *
     * @param string $token
     * @return int
     */
    public static function getCompanyLastNumber(string $token): int
    {
        return self::where('company_token', $token)->orderByDESC('id')->first()->number ?? 0;
    }

    /**
     * Check if given token exists.
     *
     * @param string $token
     * @return bool
     */
    public static function isTokenExist(string $token): bool
    {
        return !!Feedback::where('company_token', $token)->first();
    }

    /**
     * Count total numbers of feedback.
     *
     * @param string $token
     * @return int
     */
    public static function countByToken(string $token): int
    {
        return Feedback::where('company_token', $token)->count();
    }

    /**
     * Get company total count.
     *
     * @param string $token
     * @return int
     */
    public static function CacheTotalCount(string $token): int
    {
        if (!Redis::exists("feedback:{$token}:count")) {
            Redis::set("feedback:{$token}:count", self::getCompanyLastNumber($token));
        }

        self::SetTotalCount($token);

        return Redis::get("feedback:{$token}:count");
    }

    /**
     * @param string $token
     * @return int
     */
    public static function CompanyTotalCount(string $token): int
    {
        if (!Redis::exists("feedback:{$token}:count")) {
            Redis::set("feedback:{$token}:count", self::getCompanyLastNumber($token));
        }

        return Redis::get("feedback:{$token}:count");
    }

    /**
     * @param string $token
     */
    private static function SetTotalCount(string $token): void
    {
        $current_count = Redis::get("feedback:{$token}:count");

        if (!self::getCompanyLastNumber($token) && $current_count > 1) {
            Redis::set("feedback:{$token}:count", 1);
        } else {
            Redis::incr("feedback:{$token}:count");
        }
    }
}
