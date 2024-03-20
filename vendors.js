/* LightWeb 3.0.0 Standard JS Vendors   */
/* lightweb.js version [ 1710860367 ] */
var lw_version = 3.0;
/*
Nizu Core JS Library
v 1.2.1
Author RGW IT SERVICES
Web: nizu.be
*/
// File Upload Functions
function nizu_Form(htmlObjectId, apiServer, callbackFunction) {
    const form = document.querySelector('#' + htmlObjectId)
    // listen for submit even
    form.addEventListener('submit', event => {
        event.preventDefault()
        let PostData = new FormData(form)
        var object = {};
        PostData.forEach(function (value, key) {
            object[key] = value;
        });
        var data = JSON.stringify(object);
        nizu_GetData(apiServer, { a: "formsubmit", id: htmlObjectId, formData: data, lang: localStorage.getItem("LW_user_language"), uuid: localStorage.getItem("LW_uuid"), version: localStorage.getItem("LW_rel_ver") }, function (apianswer) {
            if (apianswer.success) {
                nizu_executeFunctionByName(callbackFunction, htmlObjectId, apianswer.data);
            }
        });
    })
}
String.prototype.left = function (n) {
    return this.substring(0, n);
};
function nizu_generatePin() {
    min = 0,
        max = 9999;
    return ("0" + (Math.floor(Math.random() * (max - min + 1)) + min)).substr(-4);
}
function nizu_ValidateEmail(mail) {
    if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)) {
        return (true)
    }
    return (false)
}
function nizu_guid() {
    function s4() {
        return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1);
    }
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
        s4() + '-' + s4() + s4() + s4();
}
function nizu_formatTime(time) {
    var result = false, m;
    var re = /^\s*([01]?\d|2[0-3]):?([0-5]\d)\s*$/;
    if ((m = time.match(re))) {
        result = (m[1].length == 2 ? "" : "0") + m[1] + ":" + m[2];
    }
    return result;
}
function nizu_phoneFormat(phone) {
    /*
    This function will return a correct ISO International
    Phone Number format
    */
    phone = phone.replace(/[^0-9]/g, '');
    phone = phone.replace(/(\d{3})(\d{3})(\d{3})(\d{3})/, "+($1) $2-$3-$4");
    return phone;
}
function nizu_getRandomColor() {
    var letters = '0123456789ABCDEF'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.round(Math.random() * 15)];
    }
    return color;
}
function nizu_executeFunctionByName(functionName, context /*, args */) {
    if (functionName.length > 0 && functionName != "null") {
        console.log("executing function:" + functionName);
        var args = [].slice.call(arguments).splice(2);
        var namespaces = functionName.split(".");
        var func = namespaces.pop();
        for (var i = 0; i < namespaces.length; i++) {
            context = context[namespaces[i]];
        }
        window[functionName](args);
    }
}
function nizu_GetData(nizu_serverurl, options, callback) {
    var ans = { answer: false, data: [] }
    if (options) {
        if (nizu_serverurl.length > 0) {
            $.post(nizu_serverurl, options, function (result, status) {
                if (status == "success") {
                    if (result.length > 0) {
                        if (typeof result.errcode !== 'undefined') {
                            if (result.errcode > 0) {
                                console.error("nizu error code: " + result.errcode + " nizu error message: " + result.errmsg);
                                callback(result);
                                if (callback) { callback(ans); } else {
                                    callback({ "answer": false });
                                }
                            }
                        } else {
                            if (callback) {
                                callback(result);
                            }
                        }
                    } else {
                        callback(result);
                    }
                } else {
                    console.error("getData transaction error");
                    callback(result);
                }
            });
        }
    } else {
        console.error("options incorrect");
        callback(ans);
    }
}/* my_code.js version [ 1710861733 ] */
nizu_Form("newsletter", "/api/v1/", "MyAlert");
function MyAlert(message) {
    console.info("Subscribed");
    alert("subscribed");
}/* zxy_theme.js version [ 1710861754 ] */
var LW_user_language = "{{lang_lc}}";
var LW_rel_ver = "82";
if (localStorage.getItem("LW_user_language") === null) {
    localStorage.setItem("LW_user_language", LW_user_language);
}
if (localStorage.getItem("LW_rel_ver") === null) {
    localStorage.setItem("LW_rel_ver", LW_rel_ver);
}
if (localStorage.getItem("LW_uuid") === null) {
    localStorage.setItem("LW_uuid", nizu_guid());
}
document.addEventListener("DOMContentLoaded", function (event) {
    LighWebInit();
});
function LighWebInit() {
    console.info("LighWebInit DOM Content loaded LightWeb 3.0.0 initiated\nUser Language: " + LW_user_language + "\nVersion: " + LW_rel_ver);
    /* Theme Code Begins here */
    nizu_GetData("/api/v1/", { a: "onlyhumans", LW_uuid: localStorage.getItem("LW_uuid") }, function (data) {
        console.info(data);
        if (data.success) {
            console.info("Only Human Verification: " + data.data.onlyhumans);
        }
    });
}