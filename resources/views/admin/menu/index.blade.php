@extends('layouts.app')
@extends('layouts.adds')

@section('content')
<div class="container py-4" style="width: 70%;">
    <h1>Menu Management</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Button to open Add Menu modal -->
    <button 
        type="button" 
        class="btn btn-primary mb-4" 
        id="addMenuBtn"
        data-bs-toggle="modal" 
        data-bs-target="#menuModal"
    >
        Add Menu
    </button>

    <!-- Menu list -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Menu Name</th>
                <th>Link</th>
                <th>Icon</th>
                <th>User Role</th>
                <th>Visible in Sidebar</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($menus as $menu)
                <tr>
                    <td>{{ $menu->menu_name }}</td>
                    <td>{{ $menu->link }}</td>
                    <td><i class="fa {{ $menu->icon }}"></i> {{ $menu->icon }}</td>
                    <td>{{ ucfirst($menu->user_role) }}</td>
                    <td>
                        @if($menu->visible_in_sidebar)
                            <span class="text-success">Yes</span>
                        @else
                            <span class="text-danger">No</span>
                        @endif
                    </td>
                    <td class="d-flex gap-2 align-items-center">

                        <!-- Toggle visibility form -->
                        <form method="POST" action="{{ route('admin.menu.toggle', $menu->id) }}">
                            @csrf
                            @method('PATCH')
                            <button 
                                type="submit" 
                                class="btn btn-sm btn-outline-{{ $menu->visible_in_sidebar ? 'danger' : 'success' }}" 
                                title="{{ $menu->visible_in_sidebar ? 'Remove from sidebar' : 'Add to sidebar' }}"
                            >
                                {!! $menu->visible_in_sidebar ? '&#8722;' : '&#43;' !!}
                            </button>
                        </form>

                        <!-- Edit button: triggers modal -->
                        <button 
                            type="button" 
                            class="btn btn-sm btn-outline-primary editMenuBtn" 
                            title="Edit menu"
                            data-bs-toggle="modal" 
                            data-bs-target="#menuModal"
                            data-id="{{ $menu->id }}"
                            data-menu_name="{{ $menu->menu_name }}"
                            data-link="{{ $menu->link }}"
                            data-icon="{{ $menu->icon }}"
                            data-user_role="{{ $menu->user_role }}"
                        >
                            <i class="fa fa-pencil-alt"></i>
                        </button>

                        <!-- Delete form -->
                        <form 
                            method="POST" 
                            action="{{ route('admin.menu.destroy', $menu->id) }}" 
                            onsubmit="return confirm('Delete this menu permanently?');"
                        >
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" title="Delete menu">&#10006;</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal for Add/Edit Menu -->
    <div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="menuForm" method="POST" action="{{ route('admin.menu.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="menuModalLabel">Add Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Hidden field for PUT method override -->
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <input type="hidden" name="menu_id" id="menuId">

                        <div class="mb-3">
                            <label for="menuNameInput" class="form-label">Menu Name</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="menuNameInput" 
                                name="menu_name" 
                                placeholder="Menu Name" 
                                required
                            >
                        </div>
                        <div class="mb-3">
                            <label for="linkInput" class="form-label">Link</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="linkInput" 
                                name="link" 
                                placeholder="Link (e.g. /dashboard)" 
                                required
                            >
                        </div>
                        <div class="mb-3">
                            <label for="iconInput" class="form-label">Icon</label>
                            <input 
                                type="text" 
                                class="form-control" 
                                id="iconInput" 
                                name="icon" 
                                placeholder="Icon (e.g. fa-home)"
                            >
                        </div>
                        <div class="mb-3">
                            <label for="roleSelect" class="form-label">User Role</label>
                            <select class="form-control" id="roleSelect" name="user_role" required>
                                <option value="">Select Role</option>
                                <option value="admin">Admin</option>
                                <option value="institution">Institution</option>
                                <option value="citizen">Citizen</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="modalSubmitBtn">Add Menu</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap 5 required JS (make sure bootstrap.bundle.js is loaded for modal) -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const menuModal = document.getElementById('menuModal');
    const menuForm = document.getElementById('menuForm');
    const modalTitle = menuModal.querySelector('.modal-title');
    const submitBtn = document.getElementById('modalSubmitBtn');

    const menuIdInput = document.getElementById('menuId');
    const menuNameInput = document.getElementById('menuNameInput');
    const linkInput = document.getElementById('linkInput');
    const iconInput = document.getElementById('iconInput');
    const roleSelect = document.getElementById('roleSelect');
    const formMethodInput = document.getElementById('formMethod');

    // Clear and set form to create mode
    function setCreateMode() {
        modalTitle.textContent = 'Add Menu';
        submitBtn.textContent = 'Add Menu';
        menuForm.action = "{{ route('admin.menu.store') }}";
        formMethodInput.value = 'POST';

        menuIdInput.value = '';
        menuNameInput.value = '';
        linkInput.value = '';
        iconInput.value = '';
        roleSelect.value = '';
    }

    // Set form to edit mode and populate data
    function setEditMode(menu) {
        modalTitle.textContent = 'Edit Menu';
        submitBtn.textContent = 'Update Menu';
        menuForm.action = `/admin/menu/${menu.id}`; // adjust if your route prefix differs
        formMethodInput.value = 'PUT';

        menuIdInput.value = menu.id;
        menuNameInput.value = menu.menu_name;
        linkInput.value = menu.link;
        iconInput.value = menu.icon;
        roleSelect.value = menu.user_role;
    }

    // Handle Add button click
    document.getElementById('addMenuBtn').addEventListener('click', function () {
        setCreateMode();
    });

    // Handle all edit buttons click
    document.querySelectorAll('.editMenuBtn').forEach(button => {
        button.addEventListener('click', function () {
            const menu = {
                id: this.getAttribute('data-id'),
                menu_name: this.getAttribute('data-menu_name'),
                link: this.getAttribute('data-link'),
                icon: this.getAttribute('data-icon'),
                user_role: this.getAttribute('data-user_role'),
            };
            setEditMode(menu);
        });
    });

    // Clear modal on hide to reset form next time
    menuModal.addEventListener('hidden.bs.modal', function () {
        setCreateMode();
    });
});
</script>
@endsection
