@extends('layouts.app')

@section('title', '购物车')

@section('content')
    <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">我的购物车</div>
                <div class="panel-body">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>商品信息</th>
                            <th>单价</th>
                            <th>数量</th>
                            <th>操作</th>
                        </tr>
                        </thead>
                        <tbody class="product_list">
                        @foreach($cartItems as $item)
                            <tr data-id="{{ $item->productSku->id }}">
                                <td>
                                    <input type="checkbox" name="select"
                                           value="{{ $item->productSku->id }}" {{ $item->productSku->product->on_sale ? '' : 'disabled' }}>
                                </td>
                                <td class="product_info">
                                    <div class="preview">
                                        <a target="_blank"
                                           href="{{ route('products.show', [$item->productSku->product_id]) }}">
                                            <img src="{{ $item->productSku->product->image_url }}">
                                        </a>
                                    </div>
                                    <div @if(!$item->productSku->product->on_sale) class="not_on_sale" @endif>
              <span class="product_title">
                <a target="_blank"
                   href="{{ route('products.show', [$item->productSku->product_id]) }}">{{ $item->productSku->product->title }}</a>
              </span>
                                        <span class="sku_title">{{ $item->productSku->title }}</span>
                                        @if(!$item->productSku->product->on_sale)
                                            <span class="warning">该商品已下架</span>
                                        @endif
                                    </div>
                                </td>
                                <td><span class="price">￥{{ $item->productSku->price }}</span></td>
                                <td>
                                    <input type="text" class="form-control input-sm amount"
                                           @if(!$item->productSku->product->on_sale) disabled @endif name="amount"
                                           value="{{ $item->amount }}">
                                </td>
                                <td>
                                    <button class="btn btn-xs btn-danger btn-remove">移除</button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <!-- 开始 -->
                    <div>
                        <form class="form-horizontal" role="form" id="order-form">
                            <div class="form-group">
                                <label class="control-label col-sm-3">选择收货地址</label>
                                <div class="col-sm-9 col-md-7">
                                    <select class="form-control" name="address">
                                        @foreach($addresses as $address)
                                            <option value="{{ $address->id }}">{{ $address->full_address }} {{ $address->contact_name }} {{ $address->contact_phone }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-3">备注</label>
                                <div class="col-sm-9 col-md-7">
                                    <textarea name="remark" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-3">
                                    <button type="button" class="btn btn-primary btn-create-order">提交订单</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- 结束 -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scriptsAfterJs')
    <script>
        $(document).ready(function () {
            //移除商品
            $('.btn-remove').click(function () {
                //closest() 会向上找到第一个改元素
                var sku_id = $(this).closest('tr').data('id');
                swal({
                    title: "确认要将该商品移除？",
                    icon: "warning",
                    buttons: ['取消', '确定'],
                    dangerMode: true,
                }).then(function (willDelete) {
                    if (!willDelete){
                        return;
                    }
                    axios.delete('/cart/'+sku_id).then(function (data) {
                        location.reload();
                    }).catch(function () {
                        swal('系统错误', '','error')
                    });
                })

            });
            //全选
            $('#select-all').change(function () {
                var checked = $(this).prop('checked');
                $('input[name=select][type=checkbox]:not([disabled=disabled])').each(function () {
                    $(this).prop('checked',checked)
                })
            });
            //下单
            // 监听创建订单按钮的点击事件
            $('.btn-create-order').click(function () {
                // 构建请求参数，将用户选择的地址的 id 和备注内容写入请求参数
                var rep = {
                    address_id : $('#order-form').find('select[name=address]').val(),
                    items : [],//items 是一个二维数组 由skuid和amount组成
                    remark : $('#order-form').find('textarea[name=remark]').val(),
                };
                $('table.table-striped tr').each(function () {
                    // 获取当前行的单选框
                    var check = $(this).find('input[type=checkbox][name=select]');
                    // 获取当前行中数量输入框
                    var amount = $(this).find('input[type=text][name=amount]').val();
                    if (check.prop('disabled') || !check.prop('checked') || amount<1 || isNaN(amount)){
                        return ;
                    }
                    rep.items.push({
                        sku_id:$(this).data('id'),
                        amount:amount
                    });
                    axios.post('{{route('orders.store')}}',rep)
                        .then(function (response) {
                            swal('订单提交成功', '', 'success');
                        })
                        .catch(function (error) {
                            if (error.response.status === 422) {
                                // http 状态码为 422 代表用户输入校验失败
                                var html = '<div>';
                                _.each(error.response.data.errors, function (errors) {
                                    _.each(errors, function (error) {
                                        html += error+'<br>';
                                    })
                                });
                                html += '</div>';
                                swal({content: $(html)[0], icon: 'error'})
                            } else {
                                // 其他情况应该是系统挂了
                                swal('系统错误', '', 'error');
                            }
                        });
                });
            })
        });
    </script>
@endsection