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
                    <h4 id="events_heading">
                        Events Record
                        <button type="button" class="btn btn-primary btn-sm float-end" data-bs-toggle="modal" data-bs-target="#exampleModal">Add Gold Item</button>
                        <div id="reload-space"></div>
                    </h4>   
                </div>
                <!--End Card Header and Add Button Section-->

                <!--Card Body-->
                <div class="card-body">
                    <!--Search Table-->
                    <div class="dropdown mb-5">
                        <button class="btn btn-sm btn-secondary dropdown-toggle float-end" style="width: 130px;" id="dropdown" type="button" data-bs-toggle="dropdown" aria-expanded="true">
                          Choose Type
                        </button>
                        <ul class="dropdown-menu">
                          <li><a class="dropdown-item" style="cursor: pointer;" onclick="getEvents('finish')">Finished Events</a></li>
                          <li><a class="dropdown-item" style="cursor: pointer;" onclick="getEvents('upcoming')">Upcoming events</a></li>
                          <li><a class="dropdown-item" style="cursor: pointer;" onclick="getEvents('upcomingwithseven')">Upcoming events within 7 days</a></li>
                          <li><a class="dropdown-item" style="cursor: pointer;" onclick="getEvents('finishedwithseven')">Finished events of the last 7 days</a></li>
                        </ul>
                      </div>  
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
                        <tbody id="tableData">
                           @foreach ($events as $key => $event)
                               <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$event->event_title}}</td>
                                <td style="width: 450px;">{{$event->event_description}}</td>
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

    function getEvents(type){
        var dataTable = document.getElementById('tableData');
        var heading = document.getElementById('events_heading');
        var dropdown = document.getElementById('dropdown');
        var reload = document.getElementById('')
        if(type == "finish"){   
            var req = new XMLHttpRequest();
            req.open("GET","finish-event",true);
            req.send();

            req.onreadystatechange = function(){
                if(req.readyState == 4 && req.status == 200){
                    var obj = JSON.parse(req.responseText);
                    dropdown.innerHTML = 'Finished Events';
                    heading.innerHTML ="Finished Events Record";
                    dataTable.innerHTML = "";
                    for(let i=0; i< obj.finish_events.length;i++){
                        dataTable.innerHTML += `<tr>
                                <td>`+(i+1)+`</td>
                                <td>`+obj.finish_events[i].event_title+`</td>
                                <td style="width: 450px;">`+obj.finish_events[i].event_description+`</td>
                                <td>
                                    @php
                                        $currentDate = date('Y-m-d');
                                        $currentDate = date('Y-m-d', strtotime($currentDate));
                                        $startDate = date('Y-m-d', strtotime(`+obj.finish_events[i].start_date+`));
                                        $endDate = date('Y-m-d', strtotime(`+obj.finish_events[i].end_date+`)); 
                                    
                             
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
                                <td>`+obj.finish_events[i].start_date+`</td>
                                <td>`+obj.finish_events[i].end_date+`</td>
                                <td>
                                    <button type="button" value="`+obj.finish_events[i].id+`" class="editbtn btn btn-success btn-sm">Edit</button>
                                    <button type="button" onclick="deleteEvent(`+obj.finish_events[i].id+`)" class="deleteBtn btn btn-danger btn-sm">Delete</button>
                                </td>
                            </tr>`
                    }
                }
            }
        }else if(type == "upcoming"){
           
            var req = new XMLHttpRequest();
            req.open("GET","upcoming-event",true);
            req.send();

            req.onreadystatechange = function(){
                if(req.readyState == 4 && req.status==200){
                    var obj = JSON.parse(req.responseText);
                    dropdown.innerHTML = 'Upcoming events';
                    heading.innerHTML ="Upcoming Events Record";
                    dataTable.innerHTML = "";
                    for(let i=0; i<obj.upcoming_events.length;i++){
                        dataTable.innerHTML += `<tr>
                                <td>`+(i+1)+`</td>
                                <td>`+obj.upcoming_events[i].event_title+`</td>
                                <td style="width: 450px;">`+obj.upcoming_events[i].event_description+`</td>
                                <td>
                                    <span class='badge shadow text-bg-primary'>Upcoming Events</span>
                                </td>
                                <td>`+obj.upcoming_events[i].start_date+`</td>
                                <td>`+obj.upcoming_events[i].end_date+`</td>
                                <td>
                                    <button type="button" value="`+obj.upcoming_events[i].id+`" class="editbtn btn btn-success btn-sm">Edit</button>
                                    <button type="button" onclick="deleteEvent(`+obj.upcoming_events[i].id+`)" class="deleteBtn btn btn-danger btn-sm">Delete</button>
                                </td>
                            </tr>`
                    }
                }
            }
        }else if(type == "upcomingwithseven"){
           
           var req = new XMLHttpRequest();
           req.open("GET","silver-calculate",true);
           req.send();

           req.onreadystatechange = function(){
               if(req.readyState == 4 && req.status==200){
                   var obj = JSON.parse(req.responseText);
                   dropdown.innerHTML = 'Upcoming events within 7 days';
                   heading.innerHTML ="Upcoming events within 7 days Record";
                   dataTable.innerHTML = "";
                   for(let i=0; i<obj.silver.length;i++){
                       dataTable.innerHTML += `<tr>
                           <td>`+obj.silver[i].item_code+`</td>
                           <td>`+obj.silver[i].item_name+`</td>
                           <td>`+obj.silver[i].item_tola+`</td>
                           <td><a href="{{url('calculators/`+obj.silver[i].id+`')}}" class="btn btn-danger btn-sm">Calculate</a></td>
                       </tr>`
                   }
               }
           }
        }else if(type == "finishedwithseven"){
           
           var req = new XMLHttpRequest();
           req.open("GET","silver-calculate",true);
           req.send();

           req.onreadystatechange = function(){
               if(req.readyState == 4 && req.status==200){
                   var obj = JSON.parse(req.responseText);
                   dropdown.innerHTML = 'Finished events of the last 7 days';
                   heading.innerHTML ="Finished events of the last 7 days Record";
                   dataTable.innerHTML = "";
                   for(let i=0; i<obj.silver.length;i++){
                       dataTable.innerHTML += `<tr>
                           <td>`+obj.silver[i].item_code+`</td>
                           <td>`+obj.silver[i].item_name+`</td>
                           <td>`+obj.silver[i].item_tola+`</td>
                           <td><a href="{{url('calculators/`+obj.silver[i].id+`')}}" class="btn btn-danger btn-sm">Calculate</a></td>
                       </tr>`
                   }
               }
           }
        }
    }
    
</script>
@endsection