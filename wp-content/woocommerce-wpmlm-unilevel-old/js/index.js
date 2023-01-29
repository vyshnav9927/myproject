
 

$(document).ready(function() {
    $('#allUsers').excelTableFilter();
    $('#profile_search_table').DataTable();
    
    var ewallet_all_users_table = $('#eWalletTableInside').DataTable({
        'processing': true,
        // 'serverSide': true,
        ajax: {
          url: ajaxurl + '?action=wpmlm_ewallet_users_table'
        }
    });
    
    $( '<div class="drp-dwn-arw"><i class="fa fa-filter" aria-hidden="true"></i></div>' ).appendTo( ".cb-dropdown-wrap" );
    
    $("#tooltip-desc").tooltip({
        delay: {show: 0, hide: 2000}
    });
} );