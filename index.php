<!--
    File : index.php
    Project : kgmsjq-simple-todos

    Copyright (c) 2021 kangmasjuqi

    Permission is hereby granted, free of charge, to any person obtaining a copy
    of this software and associated documentation files (the "Software"), to deal
    in the Software without restriction, including without limitation the rights
    to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
    copies of the Software, and to permit persons to whom the Software is
    furnished to do so, subject to the following conditions:

    The above copyright notice and this permission notice shall be included in all
    copies or substantial portions of the Software.

    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
    SOFTWARE.
-->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="language" content="english">
    <meta name="viewport" content="width=device-width">
    <meta name="description" content="Kgmsjq - Simple Todo App">
    <meta name="author" content="Marjuqi R.">
    <title>Kgmsjq - Simple Todo App</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="wysiwyg-editor-bootstrap/dist/css/wysiwyg.css" rel="stylesheet">
    <link href="wysiwyg-editor-bootstrap/dist/css/highlight.min.css" rel="stylesheet">
    <link href="css/app.min.css" rel="stylesheet">

</head>
<body>

<div class="container___" style="margin:0px 20px;">

    <div><br/></div>

    <!--<div id="loading-notif">Please wait..Loading...</div>-->
    <div id="error-notif">
        Request error occured...<a href='#' onclick='location.reload(true); return false;'>Reload.</a>
    </div>

    <div class="row">
        <div class="col-md-3" style="padding-right:0px;">
            <div id="table">
                <div class="table-responsive">
                   <button class="btn btn-info pull-right create-todo">Add new todo</button>
                   <ul class="list-unstyled" id="todo-list"><li style="display:none;"></li></ul>
                </div>
                <p id="reordered-saved"><small>Reordered sucessfully!</small></p>
            </div>
        </div>
        <div class="col-md-9" style="padding-left:0px;margin-top:-20px;">
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
                            <textarea class="field-element" id="todo-content" rows="15" cols="100"></textarea>
                            <input type="hidden" id="todo-id">
                        </div>
                    </form>
                    <div>
                        <button class="btn btn-danger remove-todo" id="">Remove</button>
                        <p id="changes-saved"><small>Changes automatically saved.</small></p>
                    </div>
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

<script src="js/jquery.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="wysiwyg-editor-bootstrap/dist/js/wysiwyg.js"></script>
<script src="wysiwyg-editor-bootstrap/dist/js/highlight.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#todo-content').wysiwyg({
            highlight: true
        });
        $('#new-todo-content').wysiwyg({
            highlight: true
        });
    });
</script>
<?php 
    // https://stackoverflow.com/questions/18220977/how-do-i-get-the-root-url-of-the-site
    function pathUrl($dir = __DIR__){
        $root = "";
        $dir = str_replace('\\', '/', realpath($dir));
        $root .= !empty($_SERVER['HTTPS']) ? 'https' : 'http';
        $root .= '://' . $_SERVER['HTTP_HOST'];
        if(!empty($_SERVER['CONTEXT_PREFIX'])) {
            $root .= $_SERVER['CONTEXT_PREFIX'];
            $root .= substr($dir, strlen($_SERVER[ 'CONTEXT_DOCUMENT_ROOT' ]));
        } else {
            $root .= substr($dir, strlen($_SERVER[ 'DOCUMENT_ROOT' ]));
        }
        $root .= '/';
        return $root;
    }
?>
<script >let root_url = '<?php echo pathUrl(); ?>' </script>
<script src="js/app.min.js"></script>
</body>
</html>