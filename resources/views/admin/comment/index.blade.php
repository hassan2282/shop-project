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
            <h5 class="card-title mb-3">کامنت ها</h5>
            <form class="d-flex justify-content-between">
                <div class="input-group">
                    <input type="search" name="q" value="{{\request()->q}}" class="form-control" placeholder="جست و جو ...">
                    <input type="search" name="author" value="{{\request()->author}}" class="form-control" placeholder="نویسنده">
                    <input type="search" name="parent" value="{{\request()->parent}}" class="form-control" placeholder="آیدی والد">
                    <select class="form-select" name="status" aria-label="Default select example">
                        <option selected value="">فیلتر بر اساس وضعیت</option>
                        <option value="active" {{request()->status === 'active' ? 'selected' : ''}}>فعال</option>
                        <option value="not_active" {{request()->status === 'not_active' ? 'selected' : ''}}>غیر فعال</option>
                    </select>
                    <button class="btn btn-warning" type="submit">search</button>
                </div>
            </form>
        </div>

        <div class="card-datatable table-responsive">
            <table class="datatables-users table border-top">
                <thead>
                <tr>
                    <th>آیدی</th>
                    <th>کامنت</th>
                    <th>نویسنده</th>
                    <th>مربوط به ...</th>
                    <th>والد</th>
                    <th>وضعیت</th>
                    <th>آپشن ها</th>
                </tr>
                </thead>

                <tbody>
                @foreach ($comments as $comment)
                    <tr>
                        <th>{{ $comment->id }}</th>
                        <td>{{ Str::limit($comment->body, 10, '...') }}</td>
                        <td>{{ $comment->user->first_name }}</td>
                        @if($comment->commentable)
                            <td>{{ Str::limit($comment->commentable->first()->name, 10, '...') }}
                                : {{ $comment->commentable_type === 'App\Models\Admin\Product' ? 'Product' : 'Category' }}</td>
                        @else
                            <td>{{$comment->commentable}}</td>
                        @endif

                        @if ($comment->parent_id !== null)
                            <td class="text-warning"> {{ $comment->parent->id }}
                                - {{ Str::limit($comment->parent->body, 10, '...') }}</td>
                        @else
                            <td class="text-danger">ندارد</td>
                        @endif


                        @if ($comment->status == 1)
                            <td>
                                <a href="{{ route('admin.comment.status', $comment->id) }}"
                                   class="btn rounded-pill btn-sm btn-success waves-effect waves-light">فعال</a>
                            </td>
                        @else
                            <td>
                                <a href="{{ route('admin.comment.status', $comment->id) }}"
                                   class="btn rounded-pill btn-sm btn-danger waves-effect waves-light">غیر فعال</a>
                            </td>
                        @endif

                        <td>
                            <a href="{{ route('admin.comment.show', $comment->id) }}"
                               class="btn btn-sm rounded-pill btn-info waves-effect waves-light">پاسخ</a>

                            <form id="deleteButton" class="d-inline"
                                  action="{{ route('admin.comment.delete', $comment->id) }}"
                                  method="POST">
                                @csrf
                                @method('delete')
                                @if($comment->hasChild()->count() === 0 )
                                    <button type="button"
                                            class="btn rounded-pill btn-sm btn-danger waves-effect waves-light deleteButton"
                                            id="deleteButton">حذف
                                    </button>
                                @else
                                    <div
                                            class="btn rounded-pill btn-sm btn-dark waves-effect waves-light"
                                            >کامنت والد
                                    </div>
                                @endif
                            </form>

                        </td>


                    </tr>
                @endforeach
                </tbody>
            </table>

        </div>
    </div>

    <div class="d-flex justify-content-center mt-5">
        {{ $comments->links('pagination::bootstrap-5') }}
    </div>

@endsection

@section('script')
    @include('admin.alerts.sweetalert.delete')
@endsection