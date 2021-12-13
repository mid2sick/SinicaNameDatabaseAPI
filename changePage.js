function getPage(name, SN, CBDB) {
    console.log("name = " + name);
    $.ajax({
        type: "GET",
        url: "search.php",
        data: {"name": name, "SN": SN, "CBDB": CBDB},
        // dataType: "html",
        error: function(e) {
            console.log("html reading failed: ", e);
        },
        success: function(res) {
            // console.log("success: " + res);
            document.getElementById("personPage").innerHTML = res;
        }
    })
}