
import { initInitiativeShow } from './admin/initiatives';


document.addEventListener('DOMContentLoaded', function() {
 
    if (document.getElementById('rejectModal')) {
        initInitiativeShow();
    }
});