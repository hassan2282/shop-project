@if($errors->any())
    @foreach($errors->all() as $error)
        <span class="bg-danger text-white rounded-2 p-3">{{$error}}</span>
    @endforeach
@endif
