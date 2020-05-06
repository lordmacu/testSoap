<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <title>Hello, world!</title>
        <style>
            .no-results-files{
                display:none
            }
            .loading{
                display:none
            }
        </style>
    </head>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Files Manager</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>
    <div class="container">
        <div class="mt-4">
            <button class="btn btn-success saveButton" onclick="saveFiles()">Save Files from Soap</button>
            <button class="btn btn-success" onclick="loadFiles()">Get all files</button>
            <button class="btn btn-success" onclick="getAllExtensions()">Get all Extensions</button>
        </div>
        <div class="card mt-5 loading">
            <div class="card-body text-center">
                <i class="fa fa-spinner fa-spin" style="font-size:24px"></i> Loading
            </div>
        </div>
        <div class="content mt-5"></div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.js"  crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>

    <script>

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

    </script>
</body>
</html>