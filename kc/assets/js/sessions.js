verifySession();

function verifySession(){
    if (!$.cookie('session')) {
        login();
    }
    else {
        studentsList();
    }
}

function login() {
    let pathname = window.location.pathname;

    if (pathname != "/kc/ " && pathname != "/kc/index.html") {
        location.href = 'index.html';
    }
}

function studentsList() {
    let pathname = window.location.pathname;
    if (pathname != "/kc/students.html") {
        location.href = 'students.html';
    }
}

function logout() {
    let session = getSession();

    request = $.ajax({
        url: api_url + "auth",
        type: "DELETE",
        dataType: 'json',
        headers : {
            'user_id' : session.user_id,
            'auth_token' : session.auth_token
        },
        contentType: "application/json; charset=utf-8",
    }).done(function(response) {
        $.removeCookie('session');
        login();
    }).fail(function() {
        $.removeCookie('session');
        login();
    })
}

function getSession() {
    return JSON.parse($.cookie('session'));
}

window.onbeforeunload = function() {
    removeSession();
};

window.unload = function() {
    removeSession();
};

function removeSession() {
    let pathname = window.location.pathname;
    let session = getSession();

    if (pathname != "/kc/ " && pathname != "/kc/index.html") {
        if(session.remember != 1) {
            $.removeCookie('session');
        }

        return "Session Finished!";
    }
}
