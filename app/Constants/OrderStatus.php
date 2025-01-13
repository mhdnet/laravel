<?php

namespace App\Constants;

use Illuminate\Support\Str;


/**
 * @property-read bool $isCreated
 * @property-read bool $isReceived
 * @property-read bool $isShipping
 * @property-read bool $isShipped
 * @property-read bool $isDelayed
 * @property-read bool $isResend
 * @property-read bool $isPaid
 * @property-read bool $isRejected
 * @property-read bool $isRejectedSum
 * @property-read bool $isRestored
 * @property-read bool $isCompleted
 *
 */
class OrderStatus
{
    /** أدخال بواسطة العميل */
    const Created = 'created';
    /** أستلام في مقر الشركة */
    const Received = 'received';
    /** قيد التوصيل */
    const Shipping = 'shipping';
    /** تم التوصيل */
    const Shipped = 'shipped';
    /** مؤجلً */
    const Delayed = 'delayed';

    /** تم اعادة الارسال بعد التأجيل*/
    const Resend = 'resend';

    /** تم تسديد المبلغً للعميل */
    const Paid = 'paid';

    /** تم الرفض */
    const Rejected = 'rejected';
    /** تم الرفض جزئياً */
    const RejectedSum = 'rejectedSum';
    /** تم الأسترجاع */
    const Restored = 'restored';
    /** تم أسترجاع تسديد المبلغ */
    const Completed = 'completed';

    protected string $status;

    /**
     * @param string $status
     */
    public function __construct(string $status)
    {
        $this->status = $status;
    }

/*    public const All = [
        OrderStatus::Created,
        OrderStatus::Received,
        OrderStatus::Shipping,
        OrderStatus::Shipped,
        OrderStatus::Delayed,
        OrderStatus::Resend,
        OrderStatus::Paid,
        OrderStatus::Rejected,
        OrderStatus::RejectedSum,
        OrderStatus::Restored,
        OrderStatus::Completed,
    ];*/

    public  const forChange = [self::Shipped, self::RejectedSum, self::Rejected, self::Delayed];


    public static function for(string $model): array
    {
        if (str_contains($model, "\\")) {
            $model = class_basename($model);
        }

        return match (strtolower($model)) {
            'statement' => [self::Rejected, self::RejectedSum],
            'payment' => [self::Shipped, self::RejectedSum,],
            'roster' => [self::Delayed, self::Received],
            default => []

        };
    }

    public function toReceived(): void
    {
        $this->status = OrderStatus::Received;
    }

    public function toShipping(): void {

        if($this->status == OrderStatus::Delayed) {
            $this->status = OrderStatus::Resend;
        } else
            $this->status = OrderStatus::Shipping;

    }
    public function toCompleted(string $type): void
    {
        if(!in_array($type, ['statement', 'payment']))
            return;

        switch ($this->status) {
            case OrderStatus::RejectedSum:
                $this->status = $type == 'statement' ? OrderStatus::Paid : OrderStatus::Restored;
                break;
            case OrderStatus::Paid:
            case OrderStatus::Restored:
            case OrderStatus::Rejected:
            case  OrderStatus::Shipped:
                $this->status = OrderStatus::Completed;
                break;
        }
    }

    /**
     *
     * @param string $key
     * @return bool|void
     */
    public function __get(string $key)
    {
        $key = Str::of($key);
        if (!$key->startsWith('is')) {
            return;
        }

        return $key->replace('is', '')->ucfirst()->is($this->status);

    }


 /*   public function __call(string $name, array $arguments)
    {
        $name = Str::of($name);
        if (!$name->startsWith('to')) {
            return;
        }


        $this->status = $name->replace('to', '')->ucfirst()->value();
    }*/

    public function __toString(): string
    {
        return $this->status;
    }

}
