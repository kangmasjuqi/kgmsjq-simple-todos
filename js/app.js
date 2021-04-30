
$( document ).ready(function() {

        // get highest_ordering-todo
        var id_highest_ordering = 1;
        $.ajax(
            {
                url: root_url + 'api.php?f=retrieve&d=highest_ordering',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    if(data==null){
                        $('.create-container').show();
                        $('.detail-container').hide();
                    }
                    else {
                        id_highest_ordering = data.id
                        get_todo_detail( id_highest_ordering )
                    }
                },
                error: function(xhr, textStatus, errorThrown){}
            }
        );

        // get list todo
        get_list_todos();

        // get detail todo when it's title clicked
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

        function display_note(){
            setTimeout(() => { 
                $('#changes-saved').show()
            }, 5000);
            setTimeout(() => {
                $('#changes-saved').hide()
            }, 10000);            
        }

        $( "#todo-list" ).sortable({
            placeholder : "ui-state-highlight",
            update  : function(event, ui)
            {
                var todo_id_array = new Array();
                $('#todo-list li').each(function(){
                    todo_id_array.push($(this).attr("id"));
                });

                $.ajax({
                    url: root_url + 'api.php?f=sort&d=all',
                    method:"POST",
                    data:{todo_id_array:todo_id_array},
                    success:function(data){
                        setTimeout(() => { 
                            $('#reordered-saved').show()
                        }, 500);
                        setTimeout(() => {
                            $('#reordered-saved').hide()
                        }, 3000);
                    }
                });
            }
        });

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
                            'content' : $( "textarea#new-todo-content" ).siblings( ".editor-content" ).html()
                        },
                        success: function (response) {
                            location.reload()
                        },
                        error: function(jqXHR, textStatus, errorThrown) {}
                    }
                );
            }
        );

        $("#todo-title").on("change keyup paste", function() {
            let todo_id = $("#todo-id").val()
            $('span#todo'+todo_id).html($("#todo-title").val());
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
                    error: function(jqXHR, textStatus, errorThrown) {}
                }
            );
        });

        $( "textarea#todo-content" ).siblings( ".editor-content" ).on("change keyup paste", function() {
            let todo_id = $("#todo-id").val()
            $.ajax(
                {
                    url: root_url + 'api.php?f=update&d='+todo_id,
                    type: 'POST',
                    data: {
                        'id': todo_id, 
                        'changed_data': {
                            'content' : $( "textarea#todo-content" ).siblings( ".editor-content" ).html()
                        }
                    },
                    success: function (response) {
                        display_note();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {}
                }
            );
        });

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
                                    '<li id="' + data.id + '">' +
                                        '<a class="link-detail" href="#" '+
                                            'data-todo-id="' + data.id + '">' + 
                                            '<span id="todo' + data.id + '">' + data.title + '</span>' +
                                            '<img width="12px" src="img/up-arrow.svg">' +
                                            '<img width="12px" src="img/down-arrow.svg">' +
                                        '</a>'
                                    '</li>';
                                    $( "#todo-list li:last" ).after( todo_data );

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
                            $( "textarea#todo-content" ).siblings( ".editor-content" ).html( data.content )
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
