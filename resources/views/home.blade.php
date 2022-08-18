@extends('layouts.main')
@section('styles')
<style>
    img {
        max-width: 100%;
        display: block;
        object-fit: cover;
    }

    .card {
        display: flex;
        flex-direction: column;
        width: 100%;
        overflow: hidden;
        box-shadow: 0 .1rem 1rem rgba(0, 0, 0, 0.1);
        border-radius: 1em;
        background: #ECE9E6;
        background: linear-gradient(to right, #FFFFFF, #f6f5f4);

    }

    .card__body {
        padding: 1rem;
        display: flex;
        flex-direction: column;
        gap: .5rem;
    }

    .tag {
        align-self: flex-start;
        padding: .25em .75em;
        border-radius: 1em;
        font-size: .75rem;
    }

    .tag+.tag {
        margin-left: .5em;
    }

    .tag-blue {
        background: #56CCF2;
        background: linear-gradient(to bottom, #2F80ED, #56CCF2);
        color: #fafafa;
    }

    .tag-brown {
        background: #D1913C;
        background: linear-gradient(to bottom, #FFD194, #D1913C);
        color: #fafafa;
    }

    .tag-red {
        background: #cb2d3e;
        background: linear-gradient(to bottom, #ef473a, #cb2d3e);
        color: #fafafa;
    }

    .card__body h4 {
        font-size: 1.5rem;
        text-transform: capitalize;
    }

    .card__header {
        display: flex;
        padding: 1rem;
        margin-top: auto;
    }

    .card__footer {
        display: flex;
        padding: 1rem;
        margin-top: auto;
    }

    .user {
        display: flex;
        gap: .5rem;
    }

    .user__image {
        border-radius: 50%;
    }

    .user__info>small {
        color: #666;
    }

    .bootstrap-tagsinput {
        width: 100%;
        height: 100px;
    }

    .label-info {
        background-color: #17a2b8;

    }

    .label {
        display: inline-block;
        padding: .25em .8em;
        font-size: 100%;
        font-weight: 700;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: 1rem;
        transition: color .15s ease-in-out, background-color .15s ease-in-out,
            border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    }
    .tagin-wrapper{
        height: 100px
    }
</style>

<link rel="stylesheet" href="/css/loader.css">
@endsection
@section('content')
<div class="loader d-none">
    <div class="lds-dual-ring"></div>
</div>
<div class="card text-center">
    <div class="card__header">
        <div class="row mb-2">
            <div class="col-md-12">
                <h2>PO Template</h2>
            </div>
        </div>
    </div>
    <div class="card-body text-center">
        <div class="row mb-2">
            <div class="col-md-3">
                <div class="form-group">
                    <label for="type_of_template">Type of Template</label>
                    <select  class="form-control" id="type_of_template" name="type_of_template">
                        <option value="">--choose template--</option>
                        @foreach ($getType as $key =>$val)
                            <option value="{{$val->url}}">{{$val->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-9">
                <div class="form-group">
                    <label>Tags :</label>
                    {{-- <input type="text" data-role="tagsinput" id="po" name="po" class="form-control" /> --}}
                    <input style="display: none;" type="text" name="list" id="list" class="form-control tagin"
                        data-tagin-enter value="" data-placeholder="List ... (then press enter)">
                </div>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-12">
                <div class="form-group">
                    <button type="button" id="send" class="btn btn-success">SEND</button>
                </div>
            </div>
        </div>
    </div>
</div>@endsection
@section('script')

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    $(document).ready(function(){
        const tagin = new Tagin(document.querySelector('.tagin'), {
            enter: true,
            separator: ' ',
            duplicate:false
        });
    });
    $('#send').on('click',() => {
        let list = $('#list').val();
        list = list.split(' ');
         let url = window.location.origin+"/"+$('#type_of_template').val();
         console.log(url);
         console.log(list);
        // let url = ;
        if(list){
            Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $(".loader").removeClass('d-none');
                    $.ajax({
                        type:"GET",
                        url:url,
                        data:{list:list},
                        dataType: 'json',
                        success:function(response){
                            // let obj = JSON.parse(response);
                            $(".loader").addClass('d-none');
                            console.log(response);
                            if(response.status == 1){
                                if(response.filename != "" && response.filename != null){
                                    var a = document.createElement('A');
                                    a.href = response.path;
                                    a.download = response.filename;
                                    document.body.appendChild(a);
                                    a.click();
                                    document.body.removeChild(a);
                                }else{
                                    Swal.fire(
                                    'Success',
                                    response.msg,
                                    'success'
                                );
                                }
                                
                            }else{
                                Swal.fire(
                                    'Error!',
                                    response.msg,
                                    'error'
                                );
                            }
                           
    
                        },
                        error:function(data){
                            console.log(data);
                            $(".loader").addClass('d-none');
                             Swal.fire(
                                'Error!',
                                'Something Wrong.',
                                'error'
                            )
                        }

                    });
                   
                }
            })
        }else{
            Swal.fire(
                'Warning',
                'Please,specify po number',
                'warning'
            )
        }


    });



</script>

@endsection