<html>
<head>
    <link rel="stylesheet" type="text/css" href="bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="app.css">
</head>
<body>

<div class="container___" style="margin:0px 20px;">

    <div><br/></div>

    <div id="loading-notif">Please wait..Loading...</div>
    <div id="error-notif">
        Request error occured...<a href='#' onclick='location.reload(true); return false;'>Reload.</a>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div id="table">
                <h3 style="text-align: center;">TODOS</h3>
                <div class="table-responsive">
                    <table class="table table-striped" id="todo-list">
                        <thead>
                            <th>
                                <button class="btn btn-info pull-right create-todo">
                                    Add new todo</button>
                            </th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="create-container">
                <div class="form-box">
                    <form id="form-create-todo">
                        <div>
                            <h2><input class="field-element" placeholder="todo title here.." type="text" id="new-todo-title"></h2>
                            <textarea class="field-element" placeholder="todo content here.." id="new-todo-content" rows="15" cols="100"></textarea>
                            <input type="hidden" id="new-todo-id" value="0">
                        </div>
                        <div style="margin-top:20px;">
                            <button class="btn btn-success pull-right create-todo-finish" style="width:100%;">
                                    Create</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="detail-container">
                <div class="form-box">
                    <form id="form-update-todo">
                        <div>
                            <h2><input class="field-element" type="text" id="todo-title"></h2>
                            <p id="changes-saved"><small>Changes automatically saved.</small></p>
                            <textarea class="field-element" id="todo-content" rows="15" cols="100"></textarea>
                            <input type="hidden" id="todo-id">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12 footer">
            @developed by <a href="mailto:marjuqi[dot]rahmat[at]gmail">marjuqi</a>, april 2021
        </div>
    </div>
</div>

<script src="jquery-3.6.0.min.js"></script>
<script src="bootstrap.min.js"></script>
<script src="app.js"></script>
</body>
</html>