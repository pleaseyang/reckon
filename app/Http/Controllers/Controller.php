<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $persons;

    public function getPersons(): Collection
    {
        $persons = Collection::make([
            ['name' => '开发人员A', 'pay' => 5000, 'overTime' => 1.5],
            ['name' => '开发人员B', 'pay' => 15000, 'overTime' => 1],
        ]);
        $this->persons = $persons;
        return $persons;
    }

    public function index()
    {
        $persons = $this->getPersons();
        $products = Cache::store('file')->get('products');
        return view('index', [
            'persons' => $persons,
            'products' => $products->reverse()
        ]);
    }

    public function product($productId)
    {
        $return = Cache::store('file')->get($productId);
        return view('reckon', $return);
    }

    /**
     * 计算费用
     *
     * @param array $person
     * @param array $item
     * @param bool $isOver 是否为加班费用计算
     * @return float
     */
    private function pay(array $person, array $item, bool $isOver): float
    {
        $time = $isOver ? $item['overTime'] : $item['workTime'];
        $pay = $person['pay'] / 21.75 / 8 * $time;
        if ($isOver) {
            $pay = $pay * $person['overTime'];
        }
        return round($pay, 2);
    }

    public function reckon(Request $request)
    {
        $beginDate = Carbon::make($request->post('beginDate'));
        $completeDate = Carbon::make($request->post('completeDate'));
        $persons = Collection::make($request->post('persons'));
        $persons = $persons->filter(function ($item) {
            return array_key_exists('check', $item);
        })->map(function ($item, $name) {
            unset($item['check']);
            $person = $this->getPersons()->firstWhere('name', $name);
            $item['workTimePay'] = $this->pay($person, $item, false);
            $item['overTimePay'] = $this->pay($person, $item, true);
            $item['time'] = $item['workTime'] + $item['overTime'];
            $item['pay'] = round($item['workTimePay'] + $item['overTimePay'], 2);
            return $item;
        });
        $productId = $request->post('productId');
        $productName = $request->post('productName');
        $return = [
            'beginDate' => $beginDate,
            'completeDate' => $completeDate,
            'persons' => $persons,
            'productName' => $productName,
            'productId' => $productId,
        ];
        $products = Cache::store('file')->get('products');
        if ($products === null || $products === '') {
            Cache::store('file')->put('products', Collection::make(
                [['productId' => $productId, 'productName' => $productName]]
            ));
        } else {
            if ($products->firstWhere('productId', $productId) === null) {
                Cache::store('file')->put('products', $products->push(
                    ['productId' => $productId, 'productName' => $productName])
                );
            }
        }
        Cache::store('file')->put($productId, $return);
        return view('reckon', $return);
    }
}
