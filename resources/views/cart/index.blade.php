@extends('layouts.admin')

@section('title', 'Open POS')

@section('content')
<div id="cart">
    <div class="row">
        <div class="col-md-6 col-lg-4">
            <div class="row mb-2">
                <div class="col">
                    <input type="text" class="form-control" placeholder="Scan Barcode...">
                </div>
                <div class="col">
                    <select name="" id="" class="form-control">
                        <option value="">Walking Customer</option>
                    </select>
                </div>
            </div>
            <div class="user-cart">
                <div class="card">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th class="text-right">Price</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $total = 0; @endphp
                        @foreach ($carts as $cart)
                            @php $total += $cart->pivot->quantity * $cart->price; @endphp
                            <tr>
                                <td>{{ $cart->name }}</td>
                                <td>
                                    <input type="text" class="form-control form-control-sm qty" value="{{ $cart->pivot->quantity }}">
                                    <button class="btn btn-danger btn-sm btn-delete" data-url="{{route('cart.delete')}}" data-id="{{ $cart->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                                <td class="text-right">{{ $cart->pivot->quantity * $cart->price }}đ</td>
                            </tr>
                        @endforeach                        
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col">Total:</div>
                <div class="col text-right">{{ $total }}đ</div>
            </div>
            <div class="row">
                <div class="col">
                    <button type="button" class="btn btn-danger btn-block">Cancel</button>
                </div>
                <div class="col">
                    <button type="button" class="btn btn-primary btn-block">Submit</button>
                </div>
            </div>
        </div>        
        <div class="col-md-6 col-lg-8">
            <div class="mb-2">
                <input type="text" class="form-control" placeholder="Search Product...">
            </div>
            <div class="order-product">
                @foreach ($products as $product)
                    <div class="item d-flex flex-column justify-content-center">
                        <img src="{{ Storage::url($product->image) }}" alt="">
                        <h5 class="mt-2">{{$product->name}}</h5>
                        <button class="btn btn-primary btn-add-to-cart btn-sm m-1" data-url="{{route('cart.store')}}" data-barcode="{{ $product->barcode }}">
                            <i class="far fa-plus-square"></i> Add to cart
                        </button>
                    </div>
                @endforeach                
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $(document).on('click', '.btn-add-to-cart', function () {
            $this = $(this);            
            $.ajax({
                url     :$this.data('url'),
                type    :'POST',
                data    :{
                    "_token": "{{ csrf_token() }}",
                    "barcode": $this.data('barcode') 
                },
                success :function (res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Added to cart!'
                    }).then((result) => {                        
                        if (result.isConfirmed) {                            
                            location.reload();
                        }                        
                    })                    
                },
                fail: function (res) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!'
                    })
                }
            })
        })

        $(document).on('click', '.btn-delete', function () {
            $this = $(this);            
            $.ajax({
                url     :$this.data('url'),
                type    :'DELETE',
                data    :{
                    "_token": "{{ csrf_token() }}",
                    "product_id": $this.data('id') 
                },
                success :function (res) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Delete successfully!'
                    }).then((result) => {                        
                        if (result.isConfirmed) {                            
                            location.reload();
                        }                        
                    })                    
                },
                fail: function (res) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!'
                    })
                }
            })
        })
    });
</script>
@endsection
