
$( document ).ready(
    function() {

        let root_url = 'http://localhost/kgmsjq-simple-todos/';

        var id_latest_entry = 1;

        $.ajax(
            {
                url: root_url + 'api.php?f=retrieve&d=latest_entry',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if(data==null){
                        $('.create-container').show();
                        $('.detail-container').hide();
                    }
                    else {
                        id_latest_entry = data.id
                        get_todo_detail( id_latest_entry )
                    }
                },
                error: function(xhr, textStatus, errorThrown){
                    
                }
            }
        );

        get_list_todos();

        function display_note(){
            setTimeout(() => { 
                $('#changes-saved').show()
            }, 5000);
            setTimeout(() => {
                $('#changes-saved').hide()
            }, 10000);            
        }

        $('.create-todo').click(function(e){
            e.preventDefault();
            $('.create-container').show();
            $('.detail-container').hide();
        });

        $( '#form-create-todo' ).on(
            'click',
            '.create-todo-finish',
            function() {
                $.ajax(
                    {
                        url: root_url + 'api.php?f=create&d=0',
                        type: 'POST',
                        data: {
                            'title' : $("#new-todo-title").val(),
                            'content' : $("#new-todo-content").val()
                        },
                        success: function (response) {
                            location.reload();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            // console.log(textStatus, errorThrown);
                        }
                    }
                );
            }
        );

        $("#todo-title").on("change keyup paste", function() {
            let todo_id = $("#todo-id").val()
            $.ajax(
                {
                    url: root_url + 'api.php?f=update&d='+todo_id,
                    type: 'POST',
                    data: {
                        'id': todo_id, 
                        'changed_data': {
                            'title' : $("#todo-title").val()
                        }
                    },
                    success: function (response) {
                        display_note();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // console.log(textStatus, errorThrown);
                    }
                }
            );
        });

        $("#todo-content").on("change keyup paste", function() {
            let todo_id = $("#todo-id").val()
            $.ajax(
                {
                    url: root_url + 'api.php?f=update&d='+todo_id,
                    type: 'POST',
                    data: {
                        'id': todo_id, 
                        'changed_data': {
                            'content' : $("#todo-content").val()
                        }
                    },
                    success: function (response) {
                        display_note();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        // console.log(textStatus, errorThrown);
                    }
                }
            );
        });

        $( '#todo-list' ).on(
            'click',
            '.link-detail',
            function() {
                $('.create-container').hide();
                $('.detail-container').show();
                var todo_id = $( this ).data( 'todo-id' );
                get_todo_detail( todo_id )
            }
        );

        function get_list_todos(){
            $( '#loading-notif' ).show();
            $.ajax(
                {
                    url: root_url + 'api.php?f=retrieve&d=all',
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        var todo_data = ''
                        $.each(
                            res,
                            function(key,data) {
                                todo_data =
                                    '<tr>' +
                                        '<td>' +
                                            '<a class="link-detail" href="#" '+
                                                'data-todo-id="' + data.id + '">' + 
                                                '<span>' + data.title + '</span>' +
                                            '</a>'
                                        '</td>'+
                                    '</tr>';
                                    $( "table#todo-list tr:last" ).after( todo_data );
                            }
                        );
                    },
                    complete: function(){
                        $( '#loading-notif' ).hide();
                    },
                    error: function(xhr, textStatus, errorThrown){
                        $( '#error-notif' ).show();
                    }
                }
            );
        }

        function get_todo_detail( todo_id ){
            $( '#loading-notif' ).show();
            $( '.form-box' ).removeClass( 'highlight' );
            $.ajax(
                {
                    url: root_url + 'api.php?f=retrieve&d=' + todo_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if(data==null)
                            $('.detail-container').hide();
                        else {
                            $( ".form-box" ).find( "input#todo-id" ).val( data.id );
                            $( ".form-box" ).find( "input#todo-title" ).val( data.title );
                            $( ".form-box" ).find( "textarea#todo-content" ).val( data.content );
                            $( '#loading-notif' ).hide();
                            $( '.form-box' ).addClass( 'highlight' );
                        }
                    },
                    error: function(xhr, textStatus, errorThrown){
                        $( '#error-notif' ).show();
                    }
                }
            );
        }
    }
)
