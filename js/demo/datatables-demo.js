// Call the dataTables jQuery plugin
$(document).ready(function() {
    $('#dataTable').DataTable({
        bFilter: false,       // Disable the search bar
        bLengthChange: false, // Disable the count selector
        info: false,
        ordering: false,
        paging: false
    });
});
