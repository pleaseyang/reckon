@extends('app')

@section('title', $productId)

@section('content')
    <div class="card" style="margin-top: 15px">
        <h5 class="card-header">{{ $productId }}</h5>
        <div class="card-body">
            <h4 class="card-title">{{ $productName }}</h4>
            <p class="card-subtitle mb-2 text-muted">{{ $beginDate->format('Y-m-d') }} ~ {{ $completeDate->format('Y-m-d') }}</p>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">开发人员</th>
                    <th scope="col">工作小时</th>
                    <th scope="col">加班小时</th>
                    <th scope="col">总合计</th>
                </tr>
                </thead>
                <tbody>
                @foreach($persons as $name => $person)
                <tr>
                    <th scope="row">{{ $name }}</th>
                    <td>{{ $person['workTime'] }} / {{ $person['workTimePay'] }}</td>
                    <td>{{ $person['overTime'] }} / {{ $person['overTimePay'] }}</td>
                    <td>{{ $person['time'] }} / {{ $person['pay'] }}</td>
                </tr>
                @endforeach
                <tr>
                    <th scope="row">#</th>
                    <td>{{ $persons->sum('workTime') }} / {{ $persons->sum('workTimePay') }}</td>
                    <td>{{ $persons->sum('overTime') }} / {{ $persons->sum('overTimePay') }}</td>
                    <td>{{ $persons->sum('time') }} / {{ $persons->sum('pay') }}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <a href="/" class="card-link">返回首页</a>
        </div>
    </div>
@endsection
