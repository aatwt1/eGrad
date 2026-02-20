export function initInitiativeShow() {
    // Modal za odbijanje
    const rejectModal = document.getElementById('rejectModal');
    const rejectForm = document.getElementById('rejectForm');
    const rejectTitle = document.getElementById('rejectInitiativeTitle');
    
    // Modal za prikaz razloga
    const reasonModal = document.getElementById('reasonModal');
    const reasonText = document.getElementById('rejectionReasonText');

    // Otvori modal za odbijanje
    window.openRejectModal = function(id, title) {
        if (rejectForm) {
            rejectForm.action = `/admin/initiatives/${id}/reject`;
        }
        if (rejectTitle) {
            rejectTitle.textContent = title;
        }
        if (rejectModal) {
            rejectModal.classList.remove('hidden');
        }
    };

    // Zatvori modal za odbijanje
    window.closeRejectModal = function() {
        if (rejectModal) {
            rejectModal.classList.add('hidden');
        }
        if (rejectForm) {
            rejectForm.reset();
        }
    };

    // Prikaži razlog odbijanja
    window.showRejectionReason = function(reason) {
        if (reasonText) {
            reasonText.textContent = reason;
        }
        if (reasonModal) {
            reasonModal.classList.remove('hidden');
        }
    };

    // Zatvori modal za razlog
    window.closeReasonModal = function() {
        if (reasonModal) {
            reasonModal.classList.add('hidden');
        }
    };

    // Zatvori modale klikom izvan
    window.onclick = function(event) {
        if (event.target && event.target.classList.contains('bg-opacity-50')) {
            if (rejectModal && !rejectModal.classList.contains('hidden')) {
                closeRejectModal();
            }
            if (reasonModal && !reasonModal.classList.contains('hidden')) {
                closeReasonModal();
            }
        }
    };
}