@extends('layouts.app')
@section('content')
    <div class="container mt-5">
        <h4 class="text-danger">Task Details :
            <button class="btn btn-info btn-sm float-end"data-bs-toggle="modal" data-bs-target="#addModal">Add</button>
        </h4>
    </div>
    <div class="container mt-3">
        <table class="table table-success table-bordered table-stripted" id="table_id">
            <thead class="text-center">
                <tr>
                    <th>Sl.No</th>
                    <th>Category</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @foreach ($tasks as $task)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $task->categoryName }}</td>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->description }}</td>
                        <td>{{ $task->date }}</td>
                        <td>

                            <button class="btn btn-success btn-sm"onclick="editTask('{{ $task->id }}')">Edit</button>
                            <button class="btn btn-danger btn-sm"onclick="deleteModal('{{ $task->id }}')">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- add Modal --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Adding Tasks</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <form action="#"method="post" id="upload-form">
                        @csrf
                        <div class="mb-3" id="form-category">
                            <label for="category">category:</label>
                            <select class="form-control" name="category" id="category">
                                @foreach ($categorys as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <span style="color:red" class="help-block"></span>
                        </div>
                        <div class="mb-3" id="form-title">
                            <label>Title: </label>
                            <input type="text" name="title" class="form-control" id="title">
                            <span style="color:red" class="help-block"></span>
                        </div>
                        <div class="mb-3" id="form-description">
                            <label>Description: </label>
                            <textarea name="description" class="form-control" id="description"></textarea>
                            <span style="color:red" class="help-block"></span>
                        </div>

                        <div class="mb-3" id="form-date">
                            <label>Date: </label>
                            <input type="date" name="date" class="form-control" id="date">
                            <span style="color:red" class="help-block"></span>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-dark text-white" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" onclick="saveTask()" class="btn bg-primary text-white">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-success" id="exampleModalLabel">Edit Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <form action="#"method="post" id="upload-form_edit">
                        @csrf
                        <div class="mb-3" id="form-title_edit">
                            <label>Title: </label>
                            <input type="text" name="title" class="form-control" id="title_edit">
                            <input type="hidden" name="edited_id" id="edited_id" value="">
                            <span style="color:red" class="help-block"></span>
                        </div>
                        <div class="mb-3" id="form-description_edit">
                            <label>Description: </label>
                            <textarea name="description" class="form-control" id="description_edit"></textarea>
                            <span style="color:red" class="help-block"></span>
                        </div>
                        <div class="mb-3" id="form-date_edit">
                            <label>Date: </label>
                            <input type="date" name="date" class="form-control" id="date_edit">
                            <span style="color:red" class="help-block"></span>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn bg-dark text-white" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn bg-success text-white" onclick="updateTask()">Update</button>
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
              <h5 class="modal-title text-danger" id="exampleModalLabel">Delete Task..</h5>
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
        function saveTask() {
            var category = $('#category').val();
            var title = $('#title').val();
            var description = $('#description').val();
            var date = $('#date').val();

            $.ajax({
                url: "{{ route('task.store') }}",
                method: "POST",
                data: {
                    category:category,
                    title: title,
                    description: description,
                    date: date,
                    '_token': "{{ csrf_token() }}"
                },
                success: function(response) {
                    $("#addModal").modal('hide');
                    Swal.fire({
                        icon: 'success',
                        text: 'Task added successfully',
                    })
                    location.reload();

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

        function editTask(id) {
            $.ajax({
                 type: 'POST',
                 url: "{{ route('task.edit') }}",
                 data: {
                     id: id,

                     '_token': "{{ csrf_token() }}"
                 },
                 success: function(response) {
                    console.log(response.data);
                    $('#title_edit').val(response.data.title);
                    $('#description_edit').val(response.data.description);
                    $('#date_edit').val(response.data.date);

                    $('#edited_id').val(response.data.id);
                    $('#editModal').modal('show');
                },
                error: function(xhr, status, error) {
                    // Handle any errors here
                    console.error(xhr.responseText);
                }
                });
            }
            function updateTask() {

                var title = $('#title_edit').val();
                var description = $('#description_edit').val();
                var date = $('#date_edit').val();
                var id = $('#edited_id').val();

            $.ajax({
                type: 'POST',
                url: "{{ route('task.update') }}",
                data: {
                    id: id,
                    title: title,
                    description: description,
                    date: date,
                    '_token': "{{ csrf_token() }}"
                },
                success: function(response) {
                    $('#editModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        text: 'Task updated successfully',
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
                        url: "{{ route('task.destroy') }}",
                        data: {
                            id: deleteId,

                            '_token': "{{ csrf_token() }}"
                        },
                        success: function(response) {

                            Swal.fire({
                                icon: 'success',
                                text: 'Task Deleted successfully',
                            });

                            $("#table_id").load(window.location + " #table_id");
                            $('#deleteModal').modal('hide');
                        },

                             });
                        });
                 }
    </script>
@endsection
