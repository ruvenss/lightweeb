var LW_user_language = "{{lang_lc}}";
var LW_rel_ver = "{{version}}";
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