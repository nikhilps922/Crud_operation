@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <h4 class="text-danger">Category Details:
            <button class="btn btn-primary btn-sm float-end text-white"data-bs-toggle="modal" data-bs-target="#addModal">Add
                Category</button>
        </h4>
    </div>

    <div class="container">
        <table class="table table-bordered table-stripted"id="table_id">
            <thead class="text-center">
                <tr>
                    <th>Sl.No:</th>
                    <th>Category Name:</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($categorys as $category)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $category->name }}</td>
                        <td>
                            <button
                                class="btn btn-outline-success btn-sm"onclick="editCategory('{{ $category->id }}')">Edit</button>
                            <button class="btn btn-outline-danger btn-sm"onclick="deleteModal('{{ $category->id }}')">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!--AddModal -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST" id="upload-form">
                        @csrf
                        <div class="mb-3" id="form-name">
                            <label>Category Name:</label>
                            <input type="text" class="form-control" name="name" id="name">
                            <span style="color:red" class="help-block"></span>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-dark text-white" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" onclick="saveCategory()" class="btn bg-primary text-white">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST" id="upload-form_edit">
                        @csrf
                        <div class="mb-3" id="form-name_edit">
                            <label>Category Name:</label>
                            <input type="text" class="form-control" name="name" id="name_edit">
                            <input type="hidden" name="edited_id" id="edited_id" value="">
                            <span style="color:red" class="help-block"></span>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-dark text-white" data-bs-dismiss="modal">cancel</button>
                    <button type="button" onclick="updateCategory()" class="btn bg-success text-white">update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
        {{--  Delete Modal  --}}
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title text-danger" id="exampleModalLabel">Delete Category..</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
               <h3 class="text-center text-danger p-5">Are You Sure ?</h3>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-sm bg-dark text-white" data-bs-dismiss="modal">Cancel</button>
                  <button type="button" class="btn bg-danger text-white btn-sm" id="delete_button">Delete</button>
                </div>
              </div>
            </div>
          </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script>
        function saveCategory() {
            var name = $('#name').val();

            $.ajax({
                url: "{{ route('category.store') }}",
                method: "POST",
                data: {
                    name: name,
                    '_token': "{{ csrf_token() }}"
                },
                success: function(response) {
                    $("#addModal").modal('hide');
                    Swal.fire({
                        icon: 'success',
                        text: 'Category added successfully',
                    })
                location.reload(true);
                },
                error: function(error) {
                    if (error.status == 422) {
                        console.log(error);
                        var data = error.responseJSON.errors;
                        console.log(data);
                        for (let i in data) {
                            showValidation(i, data[i][0])
                        }
                    }
                }
            });
        }

        function showValidation(name, error) {

            var input = $("#" + name);
            input.addClass('is-invalid');
            var group = $("#form-" + name);

            group.addClass('has-error');
            group.find('.help-block').text(error);
        }

        function editCategory(id) {
            $.ajax({
                type: 'POST',
                url: "{{ route('category.edit') }}",
                data: {
                    id: id,

                    '_token': "{{ csrf_token() }}"
                },
                success: function(response) {
                    console.log(response.data);
                    $('#name_edit').val(response.data.name);
                    $('#edited_id').val(response.data.id);
                    $('#editModal').modal('show');
                },
                error: function(xhr, status, error) {
                    // Handle any errors here
                    console.error(xhr.responseText);
                }
            });
        }
        function updateCategory() {

            var name = $('#name_edit').val();
            var id = $('#edited_id').val();

            $.ajax({
                type: 'POST',
                url: "{{ route('category.update') }}",
                data: {
                    id: id,
                    name: name,
                    '_token': "{{ csrf_token() }}"
                },
                success: function(response) {
                    $('#editModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        text: 'Category updated successfully',
                    });
                    $("#table_id").load(window.location + " #table_id");
                    $('.help-block').text('');
                },
                error: function(error) {
                    if (error.status == 422) {
                        console.log(error);
                        var data = error.responseJSON.errors;
                        for (let i in data) {
                            showValidationEdit(i, data[i][0])
                        }
                    }
                }
            });
        }
        function showValidationEdit(name, error) {

            var group = $("#form-" + name + "_edit");

            group.addClass('has-error');
            group.find('.help-block').text(error);
        }
        function deleteModal(id){
            $('#deleteModal').modal('show');
            deleteId = id;
            $('#delete_button').click(function(){

                $.ajax({
                    type: 'POST',
                    url: "{{ route('category.destroy') }}",
                    data: {
                        id: deleteId,
                        '_token': "{{ csrf_token() }}"
                    },
                    success: function(response) {

                        Swal.fire({
                            icon: 'success',
                            text: 'Category Deleted successfully',
                        });
                        location.reload(true);
                        $('#deleteModal').modal('hide');
                    },
                         });
                    });
             }
    </script>
@endsection
