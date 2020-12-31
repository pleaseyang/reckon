## 计算技术人员开发报价

通过时间来计算技术人员开发需要的成本报价  
开始日期和结束日期 只是展示用 不用在实际计算

### 报价计算 
我司六日/节假日都是1.5或1 加班有加班费 根据实际情况自行更改

```
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
```

### 人员分配

```
$persons = Collection::make([
            ['name' => '开发人员姓名', 'pay' => 工资/月, 'overTime' => 加班工资倍数],
            ['name' => '开发人员A', 'pay' => 5000, 'overTime' => 1.5],
            ['name' => '开发人员B', 'pay' => 15000, 'overTime' => 1],
        ])
```
### 使用

```
composer install
cp .env.example .env
php artisan serve
```
