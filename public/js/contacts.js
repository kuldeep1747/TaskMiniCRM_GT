let csrf = $('meta[name="csrf-token"]').attr('content');

// 🔹 Load contacts with filters
function loadContacts() {
    $.post('/contacts/list', {
        name: $('#searchName').val(),
        gender: $('#gender').val(),
        _token: csrf
    }, function(res) {
        let html = '';
        res.forEach(c => {
            html += `
                <tr>
                    <td><input type="checkbox" class="merge-check" value="${c.id}"></td>
                    <td>${c.full_name}</td>
                    <td>${c.email}</td>
                    <td>${c.gender}</td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick='openEditContact(${JSON.stringify(c)})'>Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteContact(${c.id})">Delete</button>
                    </td>
                </tr>
            `;
        });
        $('#contactsTable').html(html);
    });
}

// 🔹 Open Add Contact Modal
function openAddContact() {
    $('#contactForm')[0].reset();
    $('#contact_id').val('');
    $('#modalTitle').text('Add Contact');
    new bootstrap.Modal(document.getElementById('contactModal')).show();
}

// 🔹 Open Edit Contact Modal
function openEditContact(contact) {
    $('#contactForm')[0].reset();
    $('#modalTitle').text('Edit Contact');
    $('#contact_id').val(contact.id);
    $('#full_name').val(contact.full_name);
    $('#email').val(contact.email);
    $('#phone').val(contact.phone);
    $('#genderField').val(contact.gender);

    // Custom fields
    $('#customFieldsContainer input').each(function(){
        let fieldId = $(this).attr('name').match(/\d+/)[0];
        if(contact.custom_fields && contact.custom_fields[fieldId]){
            $(this).val(contact.custom_fields[fieldId]);
        }
    });

    new bootstrap.Modal(document.getElementById('contactModal')).show();
}



// 🔹 Delete Contact
function deleteContact(id) {
    if(!confirm('Are you sure?')) return;

    $.post('/contacts/delete/' + id, {_token: csrf}, function() {
        loadContacts();
    });
}

// Add/Update AJAX (URL route fixed)
$('#contactForm').submit(function(e){
    e.preventDefault();
    let formData = new FormData(this);
    formData.append('_token', csrf);

    let id = $('#contact_id').val();
    let url = id ? '/contacts/update/'+id : '/contacts/store'; // matches web.php

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        headers: { 'X-CSRF-TOKEN': csrf },
        success:function(){
            alert('Saved Successfully');
            location.reload();
        },
        error:function(xhr){
            console.log(xhr.responseText);
        }
    });
});

function openMerge() {
    let checked = $('.merge-check:checked');

    if (checked.length !== 2) {
        alert('Please select exactly 2 contacts to merge');
        return;
    }

    $('#master').html('<option value="">Select Master</option>');
    $('#secondary').html('<option value="">Select Secondary</option>');

    checked.each(function () {
        let row = $(this).closest('tr');
        let id = $(this).val();
        let name = row.find('td:nth-child(2)').text();

        $('#master').append(`<option value="${id}">${name}</option>`);
        $('#secondary').append(`<option value="${id}">${name}</option>`);
    });

    $('#master').val(checked.eq(0).val());
    $('#secondary').val(checked.eq(1).val());

    new bootstrap.Modal(document.getElementById('mergeModal')).show();
}
window.mergeContacts = function() {
    console.log('Merge button clicked');
    console.log('Master:', $('#master').val());
    console.log('Secondary:', $('#secondary').val());

    $.ajax({
        url: '/contacts/merge',
        type: 'POST',
        data: {
            master_id: $('#master').val(),
            secondary_id: $('#secondary').val(),
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
            console.log('AJAX success:', res);
            alert(res.message);

            var modal = bootstrap.Modal.getInstance(document.getElementById('mergeModal'));
            modal.hide();

            
            loadContacts();
        },
        error: function (err) {
            console.error('AJAX error:', err.responseJSON);
            alert(err.responseJSON?.error || 'Merge failed');
        }
    });
};





$(document).ready(function(){
    loadContacts();
});
