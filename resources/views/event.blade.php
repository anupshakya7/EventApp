@extends('layouts.main')
@section('main-content')

<!--Add Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Event Record</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
    <form id="AddEventForm" method="POST">
        @csrf
        <div class="modal-body">
            <ul class="alert alert-warning d-none" id="save_errorList"></ul>
            <div class="row">
                <div class="form-group mb-3 col-sm-12">
                    <label for="event_title">Event Title</label>
                    <input type="text" name="event_title" placeholder="Enter Event Title" class="form-control">
                </div>
                <div class="form-group mb-3 col-sm-12">
                    <label for="event_description">Event Description</label>
                    <textarea class="form-control" name="event_description" placeholder="Enter Event Description" rows="3"></textarea>
                </div>
                <div class="form-group mb-3 col-sm-6">
                    <label for="event_start_date">Start Date</label>
                    <input type="date" name="event_start_date" class="form-control">
                </div>
                <div class="form-group mb-3 col-sm-6">
                    <label for="event_end_date">End Date</label>
                    <input type="date" name="event_end_date" class="form-control">
                </div>
            </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Add</button>
        </div>
    </form>
      </div>
    </div>
</div>
<!--End Add Modal-->

<!--Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit & Update Event Record</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        {{-- @if (errors->all() as $err)
            @foreach ($errors->all() as $err)
                <li>{{$err}}</li>
            @endforeach
        @endif --}}

    <form action="{{url('update-event')}}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
            <ul class="alert alert-warning d-none" id="save_errorList"></ul>
            <input type="hidden" name="event_id" id="event_id">
            <div class="row">
                <div class="form-group mb-3 col-sm-12">
                    <label for="event_title">Event Title</label>
                    <input type="text" name="event_title" id="event_title" placeholder="Enter Event Title" class="form-control">
                </div>
                <div class="form-group mb-3 col-sm-12">
                    <label for="event_description">Event Description</label>
                    <textarea class="form-control" name="event_description" id="event_description" placeholder="Enter Event Description" rows="3"></textarea>
                </div>
                <div class="form-group mb-3 col-sm-6">
                    <label for="event_start_date">Start Date</label>
                    <input type="date" name="event_start_date" id="event_start_date" class="form-control">
                </div>
                <div class="form-group mb-3 col-sm-6">
                    <label for="event_end_date">End Date</label>
                    <input type="date" name="event_end_date" id="event_end_date" class="form-control">
                </div>
            </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
      </div>
    </div>
</div>
<!--End Edit Modal-->



<!-- Main Section -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mt-2">
            <!--Status Section-->
            @if(session('status'))
               <div class="alert alert-success" id="status">{{session('status')}}</div> 
            @endif
            <!--End Status Section-->
            
            <!--Card Section-->
            <div class="card shadow">
                <!--Card Header and Add Button-->
                <div class="card-header bg-white p-3">
                    <h4>
                        Events Record
                        <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Gold Item</button>
                    </h4>   
                </div>
                <!--End Card Header and Add Button Section-->

                <!--Card Body-->
                <div class="card-body">
                    <!--Search Table-->
                    <form id="searchGoldForm" method="GET" class="row mb-3">
                        <div class="col-sm-11">
                            <input type="search" name="search" placeholder="Search Item..." id="item_search" class="form-control">
                        </div>
                        <div class="col-sm-1">
                            <button class="btn btn-primary btn-sm mt-1" id="searchBtn" type="submit">Search</button>
                        </div>
                    </form>  
                    <!--End Search Table-->

                    <!--Start Table-->
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th>Title</th>
                                <th style="width:380px;">Description</th>
                                <th>Status</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                           @foreach ($events as $key => $event)
                               <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$event->event_title}}</td>
                                <td>{{$event->event_description}}</td>
                                <td>
                                    @php
                                        $currentDate = date('Y-m-d');
                                        $currentDate = date('Y-m-d', strtotime($currentDate));
                                        $startDate = date('Y-m-d', strtotime($event->start_date));
                                        $endDate = date('Y-m-d', strtotime($event->end_date)); 
                                    
                             
                                        if (($startDate > $currentDate) && ($endDate > $currentDate)){
                                            echo "<span class='badge shadow text-bg-primary'>Upcoming Events</span>";
                                        }
                                        elseif(($startDate <= $currentDate) && ($endDate >= $currentDate)){
                                            echo "<span class='badge shadow text-bg-success'>OnGoing</span>";
                                        }
                                        elseif (($endDate < $currentDate) && ($startDate < $currentDate)){
                                            echo "<span class='badge shadow text-bg-danger'>Finished Events</span>";
                                        }
                                          
                                    @endphp
                                </td>
                                <td>{{$event->start_date}}</td>
                                <td>{{$event->end_date}}</td>
                                <td>
                                    <button type="button" value="{{$event->id}}" class="editbtn btn btn-success btn-sm">Edit</button>
                                    <button type="button" onclick="deleteEvent({{$event->id}})" class="deleteBtn btn btn-danger btn-sm">Delete</button>
                                </td>
                               </tr>
                           @endforeach
                        </tbody>
                    </table>
                    <!--End Table-->
                </div>
                <!--End Card Body-->
            </div>
            <!--End Card Section-->
        </div>
    </div>
</div>
<!-- End Main Section -->
@endsection

@section('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        }
    });
    $(document).on('submit','#AddEventForm',function(e){
            e.preventDefault();
            let formData = new FormData($('#AddEventForm')[0]);
            $.ajax({
                type:'POST',
                url:'add-event',
                data:formData,
                contentType:false,
                processData:false,
                success:function(response){
                    if(response.status == 400){
                        $('#save_errorList').html("");
                        $('#save_errorList').removeClass('d-none');
                        $.each(response.errors,function(key,err_value){
                            $('#save_errorList').append('<li>'+err_value+'</li>');
                        })
                    }else if(response.status == 200){
                        $('#save_errorList').html('');
                        $('#save_errorList').addClass('d-none');
                        
                        //this.reset();
                        $('#AddEventForm').find('input').val('');
                        $('#exampleModal').modal('hide');
                        alert(response.message);
                        location.reload();
                    }
                }
            });
        });
    $(document).ready(function(){
        $(document).on("click",".editbtn",function(){
        var event_id = $(this).val();
        $('#editModal').modal('show');
        $.ajax({
            type:'GET',
            url: 'edit-event/'+event_id,
            success:function(response){
                $('#event_title').val(response.events.event_title);
                $('#event_description').val(response.events.event_description);
                $('#event_start_date').val(response.events.start_date);
                $('#event_end_date').val(response.events.end_date);
                $('#event_id').val(response.events.id);
            }
        }) 
        });
    });

    function deleteEvent(id){
        var event_id = id;
        
        var req = new XMLHttpRequest();
        req.open("GET","delete-event/"+event_id,true);
        req.send();
        req.onreadystatechange = function(){
            if(req.readyState == 4 && req.status == 200){
                var obj = JSON.parse(req.responseText);
                if(obj.status == 200){
                    alert(obj.message);
                    location.reload();
                }
            }
        }
    }
    
</script>
@endsection