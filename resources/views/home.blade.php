@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard
                    &nbsp; <a href="{{route('login-via-socialite')}}"> Login With Socialite</a>
                </div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <data>
                            <a href="{{route('add-credits')}}">Add Credits</a>
                    </data>
                    <div class="">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <td>Username</td>
                                    <td>Message</td>
                                    <td>Message_id</td>
                                    <td>is_Read</td>
                                    <td>Mark read</td>
                                    <td>Delete</td>
                                </tr>
                            </thead>
                            <tbody id="message_table_body">
                                
                            </tbody>
                        </table>
                        <span><a onclick="loadMore()" id="load-more" class="load-more">Load More</a></span>
                        <div id="norecord" style="display: none;">No More Record</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
    var offset = 0;
    var html = $('#message_table_body').html();
    function loadData(offset){
       
        $.ajax({
                headers: {'X-CSRF-TOKEN': $('input[name=_token]').val()},
                type: "POST",
                url: "/getNotification",
                data: {offset : offset},
                dataType: 'json',
                async: false,
                success: function(response) {
                    if(response.length==0){
                            $('#load-more').remove();
                            $('#norecord').css('display','block');
                        }
                   for(var i = 0; i < response.length; i++){
                     var className = '';

                        if(response[i].is_read ==1){
                            className = 'text-danger';
                        } 
                            
                        html += '<tr id=myrow'+response[i].message_id+'>';
                        html +=  '<td>'+response[i].username+'</td>';
                        html +=  '<td>'+ response[i].title +'</td>';
                        html +=  '<td>' + response[i].message_id +'</td>';
                        html +=  '<td>'+ response[i].is_read +'</td>';
                        html +=  '<td><a class="' + className + '" onclick="markAsReadUserNotification('+ response[i].message_id +');" id="mark-read-notification'+ response[i].message_id+'" class="mark-read-notification">Mark Read</a></td>';
                        html +=  '<td><a onclick="deleteUserNotification( '+ response[i].message_id + ');" id="delete-notification'+ response[i].message_id+'" class="delete-notification">Delete</a></td>';
                        html +=  '</tr>';
                   }
                $('#message_table_body').html(html);
                }
            });
    }
    function loadMore(){
      offset = offset + 5;
      loadData(offset);
    }

    $(window).on('load',function(){
        
        loadData(offset);
        
    });

    function markAsReadUserNotification(message_id) {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('input[name=_token]').val()},
                type: "POST",
                url: "/markAsReadUserNotification",
                data: {message_id : message_id},
                dataType: 'json',
                async: false,
                success: function(response) {
                   if(response == 1){
                      $('#mark-read-notification'+message_id).addClass("text-danger");
                    }
                }
            });
    }

    function deleteUserNotification(message_id) {
              if (confirm("Are you sure?")) {
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('input[name=_token]').val()},
                    type: "POST",
                    url: "/deleteUserNotification",
                    data: {message_id : message_id},
                    dataType: 'json',
                    async: false,
                    success: function(response) {
                       if(response == 1){
                          $('#myrow'+message_id).remove();
                       }
                    }
                });
              
              }
    }

  </script>
