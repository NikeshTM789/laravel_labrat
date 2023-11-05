/*----------  Datatable  ----------*/

let datatable = null;
function datatable_configs(columns, funcCalls = null, tbl_id = '#datatables'){

    datatable = $(tbl_id).DataTable({
        lengthMenu: [
            [5, 10, 25, -1],
            [5, 10, 25, "All"]
        ],
        processing: true,
        serverSide: true,
        ajax: {
            url: window.location.href,
            type: "GET"
        },
        drawCallback: function() {
            loadAfterDtTblLoaded();
            if (funcCalls) {
                if (typeof(funcCalls) != 'object') {
                    alert('2nd argument can only accept array')
                }else{
                    funcCalls.forEach((func, i) => func());
                }
            }
        },
        columns,
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "search users",
        }
    });

}