@extends('layouts.app')
@section('content')
    <div class="list-box" style="width: 500px; text-align: center;">
        <h1>Beeproger list</h1>

        {!! Form::open(['action' => 'ListController@store']) !!}
            {{Form::text('item', '',['placeholder' => 'Add new item on the list..', 'class' => 'newitem'] )}}
        {{Form::submit('add', ['class'=> 'add'])}}
        {!! Form::close() !!}
        <div class="list" style="width: 400px; margin: auto; text-align: left">
            <table>
                @if($items != null)
                    @foreach($items as $item)
                        <div id="{{$item->id}}">
                        @if($item->crossed == 'false')
                            {!! Form::open([ 'method'  => 'POST', 'action' => [ "ListController@rename",$item->id] ]) !!}
                                {{Form::text('rename'.$item->id, $item->item, ['id' => $item->id])}}
                                {{Form::submit('edit', ['class' => 'rename', 'id' => $item->id])}}
                            {!! Form::close() !!}
                            {!! Form::open([ 'method'  => 'PUT', 'route' => [ 'list.update', $item->id] ]) !!}
                                {{Form::submit('Doorstrepen',['class' => 'edit', 'id' => $item->id])}}
                            {!! Form::close() !!}

                        @else
                            {{Form::text('item', $item->item, ['style' => 'text-decoration: line-through; color: grey;'])}}
                            {!! Form::open([ 'method'  => 'PUT', 'route' => [ 'list.update', $item->id] ]) !!}
                                {{Form::submit('herstellen',['class' => 'edit', 'id' => $item->id])}}
                            {!! Form::close() !!}
                        @endif
                        {!! Form::open([ 'method'  => 'DELETE', 'route' => [ 'list.destroy', $item->id] ]) !!}
                            {{Form::hidden('id', $item->id)}}
                            {{Form::submit('verwijderen',['class' => 'remove', 'id' => $item->id])}}
                        {!! Form::close() !!}

                        <hr>
                        </div>
                    @endforeach
                    @endif
            </table>
        </div>
    </div>
    <script>
        $(".remove").click(function(e){
            e.preventDefault();
            // console.log();
            var id = e.target.id;
            console.log(id);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax(
                {
                    url: "list/"+id,
                    type: 'post', // replaced from put
                    dataType: "JSON",
                    data: {
                        _method: 'delete'
                    },
                    success: function (response)
                    {
                        console.log(response); // see the reponse sent
                        // $('.list').load(response);
                        $('#'+id).remove();
                    }
                });
        });
        $(".add").click(function(e){
            e.preventDefault();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            var item = $(".newitem").val();
            $.ajax(
                {
                    url: "list",
                    type: 'post',
                    dataType: "JSON",
                    data: {
                        item: item
                    },
                    success: function (response)
                    {
                        console.log(response); // see the reponse sent
                        // $('.list').load(response);
                        // $('#'+id).remove();
                        var id = response.id;
                        console.log(response.id);
                    var itembox = '<div id="' + id + '">';
                        itembox += '<form method="POST" action="http://beeproger.list.io/list/rename' + id + '" accept-charset="UTF-8"></form>';
                        itembox += '<input name="_token" type="hidden" value="' + $('meta[name="csrf-token"]').attr('content') + '">';
                        itembox += '<input name="item" id="' + response.id + '" type="text" value="' + response.item + '">';
                        itembox += '<input class="edit" id="'+ id +'" type="submit" value="edit">';

                        itembox += '<form method="POST" action="http://beeproger.list.io/list/' + id + '" accept-charset="UTF-8"></form>';
                        itembox +='<input name="_method" type="hidden" value="PUT">';
                        itembox += '<input name="_token" type="hidden" value=" ' + $('meta[name="csrf-token"]').attr('content') + '">';
                        itembox += '<input type="submit" value="Doorstrepen">';

                        itembox += '<form method="POST" action="http://beeproger.list.io/list/' + id +'" accept-charset="UTF-8"></form>';
                        itembox += '<input name="_method" type="hidden" value="DELETE">';
                        itembox += '<input name="_token" type="hidden" value="' + $('meta[name="csrf-token"]').attr('content') + '">';
                        itembox += '<input name="id" type="hidden" value="' + id + '">';
                        itembox += '<input class="remove" id="'+ id +'" type="submit" value="verwijderen">';
                        itembox += '<hr>';
                        itembox += '</div>';

                        $(".list").append(itembox);
                    }
                });
        });
        $(".rename").click(function(e) {
            e.preventDefault();
            var id = e.target.id;
            var item = $("input[name='rename"+id+"']").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax(
                {
                    url: "/list/rename"+id,
                    type: 'post',
                    dataType: "JSON",
                    data: {
                        item: item,
                        id: id
                    },
                    success: function (response)
                    {
                        $(".list").append();
                    }
                });
        });
        $(".edit").click(function(e) {
            e.preventDefault();
            var id = e.target.id;
            var item = $("input[name='rename"+id+"']").val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax(
                {
                    url: "/list/"+id,
                    type: 'POST',
                    dataType: "JSON",
                    data: {
                        _method: 'put',
                        item: item,
                        id: id

                    },
                    success: function(response)
                    {
                        console.log('hier');
                        console.log(response);
                        if (response == true) {
                            $("input[name='rename"+id+"']").css('text-decoration', 'line-through', 'color', 'grey');
                        } else {
                            $("input[name='rename"+id+"']").css('text-decoration', '', 'color', '');
                        }

                    }
                });
        });
    </script>
@endsection