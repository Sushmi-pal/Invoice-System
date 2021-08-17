@extends('User.UserLayout')
@can('retrieve_user')
@section('CssOrJs')

@endsection
@section('body')
    @if (session('status'))
        <div class="alert alert-success">{{session('status')}}</div>
    @endif
    <a href="{{route('user.create')}}">Add New User</a>
    <form class="d-flex" action="{{route('user.index')}}" method="GET">
        <input class="form-control me-2" type="search" name="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
    </form>
    <input type="hidden" id="order" name="order">
    <table class="table table-hover">
        <thead>
        <tr>
            @if (!request()->has('sort') and !request()->has('order'))
                <th scope="col" class="column_sort" id="sn">
                    <a href="{{request()->fullUrlWithQuery(['sort'=>'desc', 'order'=>'id'])}}">SN</a></th>
                <th scope="col" class="column_sort" id="name"><a
                        href="{{request()->fullUrlWithQuery(['sort'=>'desc', 'order'=>'name'])}}">
                        Name</a></th>
                <th scope="col" class="column_sort" id="email">
                    <a href="{{request()->fullUrlWithQuery(['sort'=>'desc', 'order'=>'email'])}}">Email</a></th>
            @elseif (request()->has('sort') and request()->has('order'))
                @php
                    if (request()->get('sort')=='desc'){
        $sort='asc';
    }
    else{
        $sort='desc';
    }
                @endphp
                <th scope="col" class="column_sort" id="sn">
                    <a href="{{request()->fullUrlWithQuery(['sort'=>$sort, 'order'=>'id'])}}">SN</a></th>
                <th scope="col" class="column_sort" id="name"><a
                        href="{{request()->fullUrlWithQuery(['sort'=>$sort, 'order'=>'name'])}}">
                        Name</a></th>
                <th scope="col" class="column_sort" id="email">
                    <a href="{{request()->fullUrlWithQuery(['sort'=>$sort, 'order'=>'email'])}}">Email</a></th>

            @endif
            <th scope="col">Edit</th>
            <th scope="col">Delete</th>
        </tr>
        </thead>
        <tbody id="CompanyTable">

        @if (isset($search))
            <?php $index = 1?>
            {{--        @dd($search->name);--}}
            @foreach($search as $user)

                <tr>
                    <td>{{$index}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td><a class="fa fa-edit" href="{{route('user.edit', $user->id)}}"></a></td>
                    <td>
                        <form action="{{url('user/'.$user->id)}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="fa fa-trash"></button>
                        </form>
                    </td>
                </tr>

                <?php $index++;?>
            @endforeach<br>
        </tbody>
    </table>
    @else
        <?php $index = 1;?>
        {{ $users -> links()}}
        @foreach($users as $user)

            <tr>
                <td>{{$index}}</td>
                <td>{{$user->name}}</td>
                <td>{{$user->email}}</td>
                <td><a class="fa fa-edit" href="{{route('user.edit', $user->id)}}"></a></td>
                <td>
                    <form action="{{url('user/'.$user->id)}}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="fa fa-trash"></button>
                    </form>
                </td>
            </tr>
            <?php $index++;?>
        @endforeach<br>
        </tbody>
    @endif
@section('JsSection')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="{{ asset('js/UserRetrieve.js') }}"></script>
@endsection
@endsection
@endcan
