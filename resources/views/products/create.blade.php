@extends('layouts.admin')

@section('content')

<h4>Add Product</h4>

<form id="addfrm" enctype="multipart/form-data">

    @csrf

    <!-- NAME -->
    <div class="mb-2">
        <label>Name</label>
        <input type="text" name="name" class="form-control">
    </div>

    <!-- PRICE -->
    <div class="mb-2">
        <label>Price</label>
        <input type="text" name="price" class="form-control">
    </div>

    <!-- DESCRIPTION -->
    <div class="mb-2">
        <label>Description</label>
        <textarea name="description" class="form-control"></textarea>
    </div>

    <!-- IMAGE -->
    <div class="mb-2">
        <label>Image</label>
        <input type="file" name="image" class="form-control">
    </div>

    <!-- SPEC -->
    <h5>Specifications</h5>

    <div id="specs"></div>

    <button type="button" id="addSpec" class="btn btn-secondary mb-3">
        Add Specification
    </button>

    <br>

    <button type="submit" class="btn btn-primary addbtn">Save</button>
    <button type="button" class="btn btn-primary addload" style="display:none;">Saving...</button>

</form>

@endsection

@section('scripts')

<script>



    // ADD SPEC
    $('#addSpec').click(function(){
        $('#specs').append(`
            <div class="row mb-2 spec-row">
                <div class="col">
                    <input type="text" name="spec_key[]" class="form-control" placeholder="Key">
                </div>
                <div class="col">
                    <input type="text" name="spec_value[]" class="form-control" placeholder="Value">
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

    $(document).ready(function(){

        // VALIDATE
        $("#addfrm").validate({
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

                let formData = new FormData(form);

                $.ajax({
                    type: 'POST',
                    url: "{{ route('products.store') }}",
                    data: formData,
                    contentType: false,
                    processData: false,

                    success: function(res){
                        Swal.fire('Success', res.message, 'success');
                    },

                    error: function(){
                        Swal.fire('Error','Something went wrong','error');
                    }
                });

                return false; // 🔥 VERY IMPORTANT
            }
        });

    });
</script>

@endsection