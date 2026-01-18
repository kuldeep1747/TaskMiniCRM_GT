<div class="modal fade" id="mergeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Merge Contacts</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Master Contact</label>
                    <select id="master" class="form-select">
                        <option value="">Select Master</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Secondary Contact</label>
                    <select id="secondary" class="form-select">
                        <option value="">Select Secondary</option>
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="mergeContacts()">
                    Merge
                </button>
                    
                </button>
            </div>

        </div>
    </div>
</div>
