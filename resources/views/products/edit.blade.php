@extends('layouts.admin')

@section('content')

<h4>Edit Product</h4>

<form id="editfrm" method="POST" enctype="multipart/form-data">

    @csrf
    <input type="hidden" id="id" value="{{ $product->id }}">

    <!-- NAME -->
    <div class="mb-2">
        <label>Name</label>
        <input type="text" name="name" value="{{ $product->name }}" class="form-control">
    </div>

    <!-- PRICE -->
    <div class="mb-2">
        <label>Price</label>
        <input type="text" name="price" value="{{ $product->price }}" class="form-control">
    </div>

    <!-- DESCRIPTION -->
    <div class="mb-2">
        <label>Description</label>
        <textarea name="description" class="form-control">{{ $product->description }}</textarea>
    </div>

    <!-- IMAGE -->
    <div class="mb-2">
        <label>Image</label>
        <input type="file" name="image" class="form-control">
        <br>
        <img src="/uploads/{{ $product->image }}?{{ time() }}" width="100">
    </div>

    <!-- SPEC -->
    <h5>Specifications</h5>

    <div id="specs">
        @foreach($product->specifications as $spec)
        <div class="row mb-2 spec-row">
            <div class="col">
                <input type="text" name="spec_key[]" value="{{ $spec->key }}" class="form-control">
            </div>
            <div class="col">
                <input type="text" name="spec_value[]" value="{{ $spec->value }}" class="form-control">
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-danger removeSpec">X</button>
            </div>
        </div>
        @endforeach
    </div>

    <button type="button" id="addSpec" class="btn btn-secondary mb-3">
        Add Specification
    </button>

    <br>

    <button type="submit" class="btn btn-primary addbtn">Update</button>
    <button type="button" class="btn btn-primary addload" style="display:none;">Updating...</button>

</form>

@endsection


@section('scripts')

<script>

$(document).ready(function(){

    // ADD SPEC
    $('#addSpec').click(function(){
        $('#specs').append(`
            <div class="row mb-2 spec-row">
                <div class="col">
                    <input type="text" name="spec_key[]" class="form-control">
                </div>
                <div class="col">
                    <input type="text" name="spec_value[]" class="form-control">
                </div>
                <div class="col-2">
                    <button type="button" class="btn btn-danger removeSpec">X</button>
                </div>
            </div>
        `);
    });

    // REMOVE SPEC
    $(document).on('click','.removeSpec',function(){
        $(this).closest('.spec-row').remove();
    });

    // VALIDATION
    $("#editfrm").validate({
        rules: {
            name: { required: true },
            price: { required: true, number: true }
        },
        messages: {
            name: "Name is required",
            price: {
                required: "Price required",
                number: "Must be number"
            }
        },

        submitHandler: function(form){

            let id = $('#id').val();

            let formData = new FormData(form);
            formData.append('_method','PUT');

            $.ajax({
                type: 'POST',
                url: "/products/" + id,
                data: formData,
                contentType: false,
                processData: false,

                beforeSend: function(){
                    $('.addbtn').hide();
                    $('.addload').show();
                },

                success: function(res){

                    Swal.fire('Success', res.message, 'success');

                    $('.addbtn').show();
                    $('.addload').hide();

                    setTimeout(function(){
                        window.location.href = "/products";
                    }, 1500);
                },

                error: function(err){

                    $('.addbtn').show();
                    $('.addload').hide();

                    if(err.status === 422){
                        let errors = err.responseJSON.errors;
                        let msg = '';

                        $.each(errors,function(k,v){
                            msg += v[0] + '\n';
                        });

                        Swal.fire('Error', msg, 'error');
                    } else {
                        Swal.fire('Error','Update failed','error');
                    }
                }
            });

            return false;
        }
    });

});
</script>

@endsection