const api_url = "http://localhost/kc_api/api/";
var per_page = 5; 
var session = getSession();
var current_page = 1; 
var total_pages = 0;

$(document).ready(function(){

    getStudents();

    $('#logout').click(function(){
        logout();
    })

    $(document).on('click', '.pagination a',function(e) {
        e.preventDefault();

        $('li').removeClass('active');
        if ($(this).data('page') == undefined) {
            if ( (current_page + 1 ) <= total_pages ) {
                current_page += 1;
            }
            $('#page-'+ current_page).addClass('active');
        }
        else {
            current_page = $(this).data('page');
            $(this).parent('li').addClass('active');
        }

        getStudents();
    });
});

function emptyTable(){
    $("#students-table > tbody").html("");
}

function getStudents(){
    var data = {
        limit : per_page,
        current_page : current_page
    }

    request = $.ajax({
        url: api_url + "students",
        type: "GET",
        dataType: 'json',
        headers : {
            'id_user' : session.id_user,
            'auth_token' : session.auth_token
        },
        data: data,
        contentType: "application/json; charset=utf-8",
    }).done(function(response) {
        emptyTable();
        fillTable(response);
        current_page = response.current_page;
    }).fail(function(){
        logout();
    })
}

function fillTable(response){

    if ($('#pagination').children().length == 0) {  
        total_pages = response.last_page;
        initializePagination();
    }

    let check = "./assets/images/check.png";
    let row_bg = "";

    $.each(response.data, function( index, value){
        row_bg = index % 2 !== 0 ? 'row-bg': null;
        $('#students-table > tbody').append(
                "<tr class='"+row_bg+"'>" 
            +       "<td>" 
            +           "<img src='"+ check +"' width='25'>"
            +       "</td>" 
            +       "<td>" 
            +           "<p>kctest002" + ('0' + value.id).slice(-2) + "</p>" 
            +           "<p>" + value.firstname + " " + value.lastname + "</p>" 
            +       "</td>"
            +       "<td>" 
            +           "<p><b>...</b></p>"
            +           "<p>Default group</p>"
            +       "</td>"
            +   "</tr>"
        );
    });
}

function initializePagination() {
    let number = 0;
    let active = '';
    let page = 0

    for(page; page < total_pages; page ++) {
        number = page + 1;
        active = number == 1 ? 'active' : null;

        $('#pagination').append(
                "<li id='page-" + number + "'class='page-item "+ active +"'>"
            +       "<a data-page='"+ number + "'class='page-link'>"+ number + "</a>"
            +   "</li>"
        );
    }

    $('#pagination').append(
            "<li class='page-item'>"
        +       "<a class='page-link' href='#'>Next&#xbb;</a>"
        +   "</li>"
    );
}
