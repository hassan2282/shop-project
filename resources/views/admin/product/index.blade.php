@extends('admin.layouts.master')

@section('content')
    @if (Session::has('alert-success'))
        <div class="alert alert-success">
            <ul>
                <li>{{ Session::get('alert-success') }}</li>
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header border-bottom">
            <h5 class="card-title mb-3">محصولات</h5>
            <form class="d-flex justify-content-between">
                <div class="input-group w-75">
                    <input type="search" name="q" value="{{request()->q}}" class="form-control" placeholder="آیدی | نام">
                    <input type="search" name="brand" value="{{request()->brand}}" class="form-control" placeholder="برند">
                    <input type="number" name="smaller" value="{{request()->smaller}}" class="form-control" placeholder="کمتر از">
                    <input type="number" name="bigger" value="{{request()->bigger}}" class="form-control" placeholder="بیشتر از">
                    <input type="search" name="category" value="{{request()->category}}" class="form-control" placeholder="دسته بندی">
                    <select class="form-select" name="status" aria-label="Default select example">
                        <option selected value="">وضعیت</option>
                        <option value="active" {{request()->status === 'active' ? 'selected' : ''}}>فعال</option>
                        <option value="not_active" {{request()->status === 'not_active' ? 'selected' : ''}}>غیر فعال</option>
                    </select>
                    <button class="btn btn-warning" type="submit">search</button>
                </div>
                <a href="{{ route('admin.product.create') }}" class="btn btn-primary float-end">اضافه کردن محصول</a>
            </form>
        </div>

        <div class="card-datatable table-responsive">
            <table class="datatables-users table border-top">
                <thead>
                <tr>
                    <th>آیدی</th>
                    <th>نام</th>
                    <th>برند</th>
                    <th>قیمت</th>
                    <th>اسلاگ</th>
                    <th>دسته بندی</th>
                    <th>وضعیت</th>
                    <th>آپشن ها</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($products as $product)
                    <tr>
                        <th>{{ $product->id }}</th>
                        <td>{{ Str::limit($product->name, 8) }}</td>
                        <td>{{ Str::limit($product->brand->original_name, 8)}}</td>
                        <td>{{ $product->price }}</td>

                        <td>{{ Str::limit($product->slug, 8) }}</td>
                        <td>{{ Str::limit($product->category->name, 8)}}</td>

                        @if ($product->status == 1)
                            <td>
                                <a href="{{ route('admin.product.status', $product->id) }}"
                                   class="btn rounded-pill btn-sm btn-success waves-effect waves-light">فعال</a>
                            </td>
                        @else
                            <td>
                                <a href="{{ route('admin.product.status', $product->id) }}"
                                   class="btn rounded-pill btn-sm btn-danger waves-effect waves-light">غیر فعال</a>
                            </td>
                        @endif

                        <td>
                            <a href="{{ route('admin.product.attributes', $product->id) }}"
                               class="btn btn-sm rounded-pill btn-warning waves-effect waves-light">ویژگی ها</a>


                            <a href="{{ route('admin.product.edit', $product->id) }}"
                               class="btn btn-sm rounded-pill btn-info waves-effect waves-light">ویرایش</a>


                            <form id="deleteButton" class="d-inline"
                                  action="{{ route('admin.product.delete', $product->id) }}"
                                  method="POST">
                                @csrf
                                @method('delete')
                                <button type="button"
                                        class="btn rounded-pill btn-sm btn-danger waves-effect waves-light deleteButton"
                                        id="deleteButton">حذف
                                </button>
                            </form>

                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>

@endsection

@section('script')
    @include('admin.alerts.sweetalert.delete')
@endsection
