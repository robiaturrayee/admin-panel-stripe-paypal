@extends('layouts.admin')

@section('content')

<a href="{{ route('products.create') }}" class="btn btn-success mb-3">
    Add Product
</a>

<table class="table" id="productTable">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Price</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
    </thead>
</table>

@endsection

@section('scripts')

<script>
let table = $('#productTable').DataTable({
    processing:true,
    serverSide:true,
    ajax:"{{ route('products.index') }}",
    columns:[
        {data:'id'},
        {data:'name'},
        {data:'price'},
        {
            data:'image',
            render:function(data){
                return '<img src="/uploads/'+data+'" width="50">';
            }
        },
        {
            data:'id',
            render:function(id){
                return `
                    <a href="/products/${id}/edit" class="btn btn-sm btn-primary">Edit</a>
                    <button class="btn btn-sm btn-danger deleteBtn" data-id="${id}">Delete</button>
                `;
            }
        }
    ]
});

// DELETE
$(document).on('click','.deleteBtn',function(){
    let id = $(this).data('id');

    Swal.fire({
        title:'Delete?',
        showCancelButton:true
    }).then(res=>{
        if(res.isConfirmed){
            $.post('/products/'+id,{_method:'DELETE'},function(){
                table.ajax.reload();
                Swal.fire('Deleted','','success');
            });
        }
    });
});
</script>

@endsection