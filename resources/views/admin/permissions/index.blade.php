@extends('layouts.app')
@extends('layouts.adds')
@section('content')
<div class="container">
    <h3 class="mb-4">Assign Role-Based Permissions</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Add Permission Form -->
    <form method="POST" action="{{ route('permissions.store') }}" class="border p-4 bg-light mb-4 rounded">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <label>Role</label>
                <select name="role" class="form-control" required>
                    @foreach ($roles as $role)
                        <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4">
                <label>Menu</label>
                <select name="menu" class="form-control" required>
                    @foreach ($menus as $menu)
                        <option value="{{ $menu }}">{{ $menu }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-4 mt-2">
                <label>Permissions</label><br>
                @foreach (['can_read'=>'Read', 'can_create'=>'Create', 'can_edit'=>'Edit', 'can_delete'=>'Delete', 'can_approve'=>'Approve'] as $field => $label)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="{{ $field }}" value="1">
                        <label class="form-check-label">{{ $label }}</label>
                    </div>
                @endforeach
            </div>
        </div>
        <button class="btn btn-primary mt-3">Add Permission</button>
    </form>

    <!-- Permissions Table -->
    <table class="table table-bordered table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Role</th>
                <th>Menu</th>
                <th>Read</th>
                <th>Create</th>
                <th>Edit</th>
                <th>Delete</th>
                <th>Approve</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($permissions as $permission)
            <tr>
                <td>{{ ucfirst($permission->role) }}</td>
                <td>{{ $permission->menu }}</td>
                <td><input type="checkbox" disabled {{ $permission->can_read ? 'checked' : '' }}></td>
                <td><input type="checkbox" disabled {{ $permission->can_create ? 'checked' : '' }}></td>
                <td><input type="checkbox" disabled {{ $permission->can_edit ? 'checked' : '' }}></td>
                <td><input type="checkbox" disabled {{ $permission->can_delete ? 'checked' : '' }}></td>
                <td><input type="checkbox" disabled {{ $permission->can_approve ? 'checked' : '' }}></td>
                <td>
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $permission->id }}">Edit</button>
                </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal{{ $permission->id }}" tabindex="-1">
              <div class="modal-dialog">
                <form method="POST" action="{{ route('permissions.update', $permission->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Edit Permission</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>
                      <div class="modal-body">
                          <div class="mb-3">
                              <label>Role</label>
                              <select name="role" class="form-control">
                                  @foreach ($roles as $role)
                                      <option value="{{ $role }}" {{ $permission->role == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                                  @endforeach
                              </select>
                          </div>
                          <div class="mb-3">
                              <label>Menu</label>
                              <select name="menu" class="form-control">
                                  @foreach ($menus as $menu)
                                      <option value="{{ $menu }}" {{ $permission->menu == $menu ? 'selected' : '' }}>{{ $menu }}</option>
                                  @endforeach
                              </select>
                          </div>
                          <div>
                              @foreach (['can_read'=>'Read', 'can_create'=>'Create', 'can_edit'=>'Edit', 'can_delete'=>'Delete', 'can_approve'=>'Approve'] as $field => $label)
                                  <div class="form-check form-check-inline">
                                      <input class="form-check-input" type="checkbox" name="{{ $field }}" value="1" {{ $permission->$field ? 'checked' : '' }}>
                                      <label class="form-check-label">{{ $label }}</label>
                                  </div>
                              @endforeach
                          </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save Changes</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      </div>
                    </div>
                </form>
              </div>
            </div>
        @endforeach
        </tbody>
    </table>
</div>
@endsection


@include('layouts.footer')