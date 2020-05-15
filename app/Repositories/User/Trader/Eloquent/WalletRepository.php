<?php

namespace App\Repositories\User\Trader\Eloquent;
use App\Models\User\Wallet;
use App\Repositories\User\Admin\Interfaces\StockItemInterface;
use App\Repositories\User\Admin\Interfaces\TransactionInterface;
use App\Repositories\User\Interfaces\NotificationInterface;
use App\Repositories\User\Trader\Interfaces\WalletInterface;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\DB;

class WalletRepository extends BaseRepository implements WalletInterface
{
    protected $model;

    public function __construct(Wallet $model)
    {
        $this->model = $model;
    }

    public function findStockItem(int $id)
    {
        return $this->model->where('stock_item_id', $id)->first();
    }

    public function insert(array $parameters)
    {
        return $this->model->insert($parameters);
    }

    public function createUnavailableWallet($userID)
    {
        $date = now();
        $activeStockItems = app(StockItemInterface::class)->getActiveList();
        $wallet = $this->getByConditions(['user_id' => $userID]);
        $unavailalbeWallets = $activeStockItems->whereNotIn('id', $wallet->pluck('stock_item_id')->toArray());
        $walletParameters = [];

        foreach ($unavailalbeWallets->pluck('id') as $stockItemID) {
            $walletParameters[] = [
                'user_id' => $userID,
                'stock_item_id' => $stockItemID,
                'created_at' => $date,
                'updated_at' => $date,
            ];
        }

        return $this->insert($walletParameters);
    }

    public function firstOrFail(array $conditions)
    {
        return $this->model->where($conditions)->firstOrFail();
    }

    public function count(array $conditions)
    {
        return $this->model->where($conditions)->count();
    }

    public function updateAllByConditions(array $attributes, array $conditions)
    {
        return $this->model->where($conditions)->update($attributes);
    }

    public function updateWalletBalance($conditions, $amount)
    {
        try {
            DB::beginTransaction();
            $walletAmount = [];
            $transactionParameters = [];
            $notificationParameters = [];

            $this->model->orderBy('id')->where($conditions)->with(['stockItem'])
                ->chunkById(200, function ($wallets) use ($amount, $walletAmount, $transactionParameters, $notificationParameters) {
                    if (empty($wallets)) {
                        return false;
                    }

                    foreach ($wallets as $wallet) {
                        if ($wallet->user->is_active == ACTIVE_STATUS_ACTIVE) {
                            $walletAmount[] = [
                                'conditions' => ['id' => $wallet->id, 'is_active' => ACTIVE_STATUS_ACTIVE],
                                'fields' => [
                                    'primary_balance' => ['increment', bcmul($amount, '1')],
                                ]
                            ];
                            $date = now();
                            $transactionParameters[] = [
                                'user_id' => $wallet->user_id,
                                'stock_item_id' => $wallet->stock_item_id,
                                'model_name' => null,
                                'model_id' => null,
                                'transaction_type' => TRANSACTION_TYPE_DEBIT,
                                'amount' => bcmul($amount, '-1'),
                                'journal' => DECREASED_FROM_SYSTEM_ON_TRANSFER_BY_ADMIN,
                                'updated_at' => $date,
                                'created_at' => $date,
                            ];
                            $transactionParameters[] = [
                                'user_id' => $wallet->user_id,
                                'stock_item_id' => $wallet->stock_item_id,
                                'model_name' => get_class($wallet),
                                'model_id' => $wallet->id,
                                'transaction_type' => TRANSACTION_TYPE_CREDIT,
                                'amount' => bcmul($amount, '1'),
                                'journal' => INCREASED_TO_USER_WALLET_ON_TRANSFER_BY_ADMIN,
                                'updated_at' => $date,
                                'created_at' => $date,
                            ];
                            $notificationParameters[] = [
                                'user_id' => $wallet->user_id,
                                'data' => __("Your :currency wallet has been increased with :amount :currency by system.", [
                                    'amount' => $amount,
                                    'currency' => $wallet->stockItem->item,
                                ]),
                                'created_at' => $date,
                                'updated_at' => $date,
                            ];
                        }
                    }

                    $this->bulkUpdate($walletAmount);
                    app(TransactionInterface::class)->insert($transactionParameters);
                    app(NotificationInterface::class)->insert($notificationParameters);

                });

            DB::commit();

            return true;
        }
        catch (\Exception $exception)
        {
            DB::rollBack();

            return false;
        }
    }
}