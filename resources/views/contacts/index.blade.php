@extends('layouts.app')
@section('content')

<div class="container">
    <h3>Mini CRM</h3>

    <!-- Filters -->
    <div class="row mb-3">
        <div class="col-md-3">
            <input type="text" id="searchName" class="form-control" placeholder="Search Name">
        </div>
        <div class="col-md-2">
            <select id="gender" class="form-select">
                <option value="">All Gender</option>
                <option>Male</option>
                <option>Female</option>
                <option>Other</option>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary" onclick="loadContacts()">Filter</button>
        </div>
        <div class="col-md-3">
            <button class="btn btn-success" onclick="openAddContact()">Add Contact</button>
        </div>
    </div>

    <!-- Contacts Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Select</th>
                <th>Name</th>
                <th>Email</th>
                <th>Gender</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="contactsTable"></tbody>
    </table>

    <!-- Merge Button -->
    <button class="btn btn-warning" onclick="openMerge()">Merge Selected</button>
</div>

<!-- Merge Modal -->
@include('contacts.merge-modal')

<!-- Add/Edit Contact Modal -->
<div class="modal fade" id="contactModal" tabindex="-1">
    <div class="modal-dialog">
        <form id="contactForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="contact_id" id="contact_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Contact</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Full Name</label>
                        <input type="text" name="full_name" id="full_name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" id="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Gender</label>
                        <select name="gender" id="genderField" class="form-select">
                            <option value="">Select Gender</option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Profile Image</label>
                        <input type="file" name="profile_image" id="profile_image" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Additional Document</label>
                        <input type="file" name="document" id="document" class="form-control">
                    </div>

                    <!-- Dynamic Custom Fields -->
                    <div id="customFieldsContainer">
                        @foreach($customFields as $field)
                            <div class="mb-3">
                                <label>{{ $field->name }}</label>
                                <input type="text" name="custom_fields[{{ $field->id }}]" class="form-control">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
