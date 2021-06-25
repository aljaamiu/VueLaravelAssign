<template>
    <div>
        
        <nav class="navbar navbar-light bg-success mt-3">
        
        <!-- <FlashMessage></FlashMessage> -->
        </nav>

        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li class="nav-item active">
                    </li>
                    <li class="nav-item">
                    </li>
                    <li class="nav-item">
                    </li>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                    <button type="button" class="btn btn-outline-success my-2 my-sm-0" >Search</button>
                </form>
            </div>
        </nav>

        <h2 style="font-family:times new romans; font-weight:bold; color:green;">Add A Subscriber </h2>
        <form @submit.prevent="addSubscriber" class="mb-3">
            <div class="row">
                <div class="form-group">
                    <div class="col-md-12">
                        <label>Name<span style="color: red;">*</span></label>
                        <input type="text" autofocus="autofocus" class="form-control" v-model="subscriber.name" autocomplete="off">
                    </div>
                </div>
                <!-- <div class="form-group">
                    <div class="col-md-12">
                        <label>Last Name<span style="color: red;">*</span></label>
                        <input type="text" class="form-control" v-model="subscriber.last_name" autocomplete="off">
                    </div>
                </div> -->
                <div class="form-group">
                    <div class="col-md-12">
                        <label>Private<span style="color: red;">*</span></label>
                        <select name="" class="form-control private" id="private" v-model="subscriber.is_private">
                            <option value="">----</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <label>Organisation<span style="color: red;">*</span></label>
                        <select name="" id="org" class="form-control org"  v-model="subscriber.org_id">
                            <option value="">-----</option>
                            <option value="1">KNUST</option>
                            <option value="2">UN</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <label>Active</label>
                        <select name="" id="active" class="form-control active"  v-model="subscriber.is_active">
                            <option value="">-----</option>
                            <option value="1">Yes</option>
                            <option value="2">No</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <label>Phone No.<span style="color: red;">*</span></label>
                        <input type="text" class="form-control" v-model="subscriber.phone" autocomplete="off">
                    </div>
                </div>
            
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <button id="submit" type="submit" class="btn btn-outline-success btn-block">Save</button>
                </div>
                <div class="form-group col-md-6">
                    <button type="button" @click="clearForm()" class="btn btn-outline-danger btn-block">Refresh</button>
                </div>
            </div>
        </form>

        <div class="card">
            <div class="card-body">
                <div class="card-header bg-success" style="text-transform:uppercase;  color:#fff; font-size:25px; font-family:times new romans; text-align:center">LARAVEL VUE CRUD <b>ASSIGNMENT</b> APP </div>
                <div style="overflow-x: auto">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                            <th>ID</th>
                            <th>Value</th>
                            <th>Phone</th>
                            <th class="text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="subscriber in subscribers" v-bind:key="subscriber.id">
                                <td>{{subscriber.id}}</td>
                                <td>{{subscriber.name}}</td>
                                <td>{{subscriber.phone}}</td>
                                <td>
                                    <a @click="editSubscriber(subscriber)"  class="btn btn-success btn-xs" href="#" ><i class="fa fa-pencil"></i> </a>
                                    <a @click="deleteSubscriber(subscriber.id)" class="btn btn-danger btn-xs" href="#"> <i class="fa fa-trash text-red"></i></a>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </div>
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchSubscribers(pagination.prev_page_url)">Previous</a></li>

                        <li class="page-item disabled"><a class="page-link text-dark" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>
                    
                        <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link" href="#" @click="fetchSubscribers(pagination.next_page_url)">Next</a></li>
                    </ul>
                </nav>
            </div>
        </div>

    </div>

</template>

<style>
    .image{
        width: 30px !important;
    }
    td{
        font-family:Arial, Helvetica, sans-serif;
        font-size: 18px;
        text-align: center;
    }
    th{
        font-family:Arial, Helvetica, sans-serif;
        font-size: 18px;
        text-align: center;
    }
</style>

<script>

    export default {
        data() {
            return {

                subscribers: [],
                subscriber: {
                    name: '',
                    is_active: '',
                    is_private: '',
                    v_id: '',
                    phone: '',
                    org_id: '',
                    subscriber_property_id: '',
                    subscriber_id: '',
                    styleObject: {
                        width: '100px',
                        height: '100px'
                    }
                },
                pagination: {},
                msgs: {},
                edit: false
            
            };
        },

        created() {
            this.fetchSubscribers();
        },

        methods: {


            fetchSubscribers(page_url) {
                let paginate = this;
                page_url = page_url || '/api/getall';
                fetch(page_url)
                    .then(res => res.json())
                    .then(res => {
                        this.subscribers = res.data;
                        // paginate.makePagination(res.meta, res.links);
                        let mylinks = res.links;
                        let pagination = {
                            current_page: res.current_page,
                            last_page: res.last_page,
                            // next_page_url: mylinks.next,
                            next_page_url: res.next_page_url,
                            // prev_page_url: mylinks.prev
                            prev_page_url: res.prev_page_url
                        };

                        this.pagination = pagination;

                    })
                    .catch(err => console.log(err));
                },


                // makePagination(meta, links) {
                //     let pagination = {
                //         current_page: meta.current_page,
                //         last_page: meta.last_page,
                //         next_page_url: links.next,
                //         prev_page_url: links.prev
                //     };

                //     this.pagination = pagination;
                // },
                showModal(subscriber) {
                    this.subscriber.v_id = subscriber.v_id;
                    this.subscriber.id = subscriber.id;
                    this.subscriber.subscriber_id = subscriber.id;
                    $('#studentDeleteModal').modal('show');
                },

                deleteSubscriber(v_id) {
                    
                if (confirm('Are You Sure You Want to Delete?' )) {
                    fetch(`api/remove/${v_id}`, {
                    method: 'get'
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert('Subscriber Removed Successfully!');
                        this.fetchSubscribers();
                    })
                    .catch(err => console.log(err));
                }
            },



            // Add New subscriber

            addSubscriber() {
                if (this.edit === false) {
                    // Add
                    var token = $('meta[name="csrf-token"]').attr('content');
                    console.log(token);
                    fetch('api/save', {
                        method: 'post',
                        body: JSON.stringify(this.subscriber),
                        headers: {
                            'content-type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })
                    // .then(res => res.json())
                    // .then(data => {
                    //     this.clearForm();
                    //     alert('Subscriber Added Successfully');
                    //     this.fetchSubscribers();
                    //     // this.flashMessage.success({
                    //     //     title: 'Success',
                    //     //     message: 'Subscriber Added Successfully!',
                    //     //     time: 5000
                    //     // });
                    .then(res => res.json())
                    .then(data => {
                        this.msgs = data.data;
                        // alert(data.success);
                        if(data.success == 'Done'){
                            //
                            this.clearForm();
                            alert('Subscriber Added Successfully!');
                            this.fetchSubscribers();
                        }else{
                            //
                            alert(data.success);
                        }
                    })
                    .catch(err => console.log(err));
                } else {
                    // Update
                    var token = $('meta[name="csrf-token"]').attr('content');
                    console.log(token);
                    fetch('api/update', {
                        method: 'post',
                        body: JSON.stringify(this.subscriber),
                        headers: {
                            'content-type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                    
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.msgs = data.data;
                        // alert(data.success);
                        if(data.success == 'Done'){
                            //
                            this.clearForm();
                            alert('Subscriber Updated Successfully!');
                            this.fetchSubscribers();
                        }else{
                            //
                            alert(data.success);
                        }
                        // this.clearForm();
                        // alert('Subscriber Updated Successfully!');
                        // this.fetchSubscribers();
                    })
                    .catch(err => console.log(err));
                    
                }
            },
            editSubscriber(subscriber) {
                this.edit = true;
                this.subscriber.v_id = subscriber.v_id;
                this.subscriber.id = subscriber.id;
                this.subscriber.subscriber_id = subscriber.id;
                this.subscriber.name = subscriber.name;
                this.subscriber.is_active = subscriber.is_active;
                this.subscriber.phone = subscriber.phone;
                this.subscriber.is_private = subscriber.is_private;
                this.subscriber.org_id = subscriber.org_id;
                // $('#active option:eq('+1+')').prop('selected', true);
                // $('#private option:eq('+2+')').prop('selected', true);
                // $('#org option:eq('+1+')').prop('selected', true);
                // $('.active option[value="'+1+'"]').prop('selected', true);
                // $('.private option[value="'+1+'"]').prop('selected', true);
                // $('.org option[value="'+2+'"]').prop('selected', true);
                $('#submit').text('Update');

                // alert(subscriber.phone)
                // alert(subscriber.is_private)
                // alert(subscriber.is_active)
                // alert(subscriber.org_id)
            
            },
            showSubscriber(subscriber) {
                //   this.edit = true;
                this.subscriber.id = subscriber.id;
                this.subscriber.subscriber_id = subscriber.id;
                this.subscriber.name = subscriber.name;
                this.subscriber.is_active = subscriber.is_active;
                this.subscriber.phone = subscriber.phone;
                this.subscriber.is_private = subscriber.is_private;
                this.subscriber.org_id = subscriber.org_id;
            },
            clearForm() {
                this.edit = false;
                this.subscriber.id = null;
                this.subscriber.subscriber_id = null;
                this.subscriber.name = '';
                this.subscriber.gender = '';
                this.subscriber.phone = '';
                this.subscriber.is_active = '';
                this.subscriber.is_private = '';
                this.subscriber.org_id = '';
                $('#submit').text('Save');
            },

            ///
            alertmsg() {
                //
            }
        }
    };

    $(document).ready(function(){
        if (this.edit === false) {
            $("#get_avater").hide();
        }
        
    })
</script>
