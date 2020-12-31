@extends('app')

@section('title', '计算费用')

@section('content')
    <nav class="navbar fixed-top navbar-light bg-light">
        <a class="navbar-brand" href="/">计算费用</a>
    </nav>
    <div class="row" style="margin-top: 65px">
        <div class="col">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reckonModal">
                新建计算费用
            </button>
        </div>
        <div class="w-100"></div>
        <div class="col" style="margin-top: 15px">
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">项目名称</th>
                    <th scope="col">操作</th>
                </tr>
                </thead>
                <tbody>
                @if($products === null || $products === '')
                    <tr>
                        <th colspan="3" scope="row" class="text-center">暂无费用信息</th>
                    </tr>
                @else
                @foreach($products->reverse() as $product)
                    <tr>
                        <th scope="row">{{ $product['productId'] }}</th>
                        <td>{{ $product['productName'] }}</td>
                        <td>
                            <a class="btn btn-primary" href="{{ route('product', ['productId' => $product['productId']]) }}" role="button">查看详情</a>
                        </td>
                    </tr>
                @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
    <!-- Button trigger modal -->
    <!-- Modal -->
    <div class="modal fade" id="reckonModal" tabindex="-1" role="dialog" aria-labelledby="reckonModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form method="post" action="{{ route('reckon') }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="reckonModalLabel">新建计算费用</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="productName">项目名称</label>
                            <input type="text" class="form-control" id="productName" name="productName" placeholder=""
                                   value="" required>
                            <input type="hidden" class="form-control" id="productId" name="productId" placeholder=""
                                   value="{{ (string)\Illuminate\Support\Str::uuid() }}">
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="beginDate">开始日期</label>
                                    <input type="date" class="form-control" id="beginDate" name="beginDate" placeholder=""
                                           value="{{ \Illuminate\Support\Carbon::now()->format('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="completeDate">完成日期</label>
                                    <input type="date" class="form-control" id="completeDate" name="completeDate" placeholder=""
                                           value="{{ \Illuminate\Support\Carbon::now()->addDays(7)->format('Y-m-d') }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>制作人员</label>
                            @foreach($persons as $person)
                                <div class="form-check">
                                    <input class="form-check-input" name="persons[{{ $person['name'] }}][check]" type="checkbox"
                                           value="1" id="person_{{ $person['name'] }}">
                                    <label class="form-check-label" for="person_{{ $person['name'] }}">
                                        {{ $person['name'] }} {{ $person['overTime'] }}
                                    </label>
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="person[workTime][{{ $person['name'] }}]">工作小时</label>
                                                <input type="number" min="0.5" step="0.1" class="form-control" value="4"
                                                       id="person[workTime][{{ $person['name'] }}]"
                                                       name="persons[{{ $person['name'] }}][workTime]" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="person[overTime][{{ $person['name'] }}]">加班小时</label>
                                                <input type="number" min="0" step="0.1" class="form-control" value="0"
                                                       id="person[overTime][{{ $person['name'] }}]"
                                                       name="persons[{{ $person['name'] }}][overTime]" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        @csrf
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                        <button type="submit" class="btn btn-primary">计算</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
