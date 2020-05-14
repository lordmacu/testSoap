function loadFiles() {
    // show files
    $(".loading").show();
    $.ajax('/loadReports.php?action=getrAllfiles', {
        type: 'GET', // http method
        success: function (data, status, xhr) {
            var data = JSON.parse(data);

            if (data.size == 0) {
                $(".content").html("No files results");
            } else {
                $(".content").html(data.compiled);
            }

            $(".loading").hide();
            $(".content").show();
        },
        error: function (jqXhr, textStatus, errorMessage) {
            $(".loading").hide();
            $(".content").show();
        }
    });
}

function saveFiles() {

//save files
    $(".saveButton").hide();
    $(".loading").show();
    $(".content").hide();

    $.ajax('/loadReports.php?action=saveFiles', {
        type: 'GET', // http method
        success: function (data, status, xhr) {
            loadFiles(); // when the system import files show it
            $(".loading").hide();
            $(".saveButton").show();
        },
        error: function (jqXhr, textStatus, errorMessage) {
            $(".loading").hide();
            $(".saveButton").show();
        }
    });
}


function getAllExtensions() {
    /// get extentions by ajax
    $(".loading").show();
    $.ajax('/loadReports.php?action=getAllExtensions', {
        type: 'GET', // http method
        success: function (data, status, xhr) {
            var data = JSON.parse(data);
            if (data.size == 0) {
                $(".content").html("No extensions results");
            } else {
                $(".content").html(data.compiled);
            }
            $(".loading").hide();
            $(".content").show();
        },
        error: function (jqXhr, textStatus, errorMessage) {
            $(".loading").hide();
            $(".content").show();
        }
    });
}
loadFiles();