<template>
    <div class="container-fluid p-0 m-0">
        <div class="mt-1">
            <h3>SMS Notification for cancelled appointment<i class="nav-icon fa fa-sms fa-lg text-success"></i></h3>
            <code class="text-muted"> - please suggest a page title (MIS dev) </code>
        </div>
        <div>
            <div class="sticky-top bg-light m-0 p-0">
                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group float-right mb-1 col-md-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-calendar text-info fa-lg"></i> <span> Select Date </span></span>
                            </div>
                            <input id="bydate" v-model="filterByDate" type="date" class="form-control font-weight-bold" placeholder="Search">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group float-right mb-1 col-md-12">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-map-marker-alt text-info fa-lg"></i> <span> Select Department </span></span>
                            </div>
                            <select v-model="sel_dept" name="" id="selected_department" class="form-control font-weight-bold">
                                <option value="all">ALL (OPD)</option>
                                <option :value="dept.tscode" v-for="dept in departments" :key="dept.tscode">{{dept.tsdesc}}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row bg-secondary text-light font-weight-bold">
                    <div v-show="allDept == 1" class="col-md-2">Dept.</div>
                    <div class="col-md-1">Type</div>
                    <div class="col-md-1">Time</div>
                    <div class="col-md-1">Hosp #</div>
                    <div class="col-md-2">Name</div>
                    <div class="col-md-2">Contact #</div>
                    <div class="col-md-2">Scheduled By</div>
                </div>
            </div>
            <!-- <div class="mt-2" v-if="filteredPatients == ''">
                <h4 class="font-weight-bold">No scheduled patient</h4>
            </div> -->
            <tr v-if=" !filteredPatients " >
                <span class=" mt-3 spinner-grow text-info" style="width: 5rem; height: 5rem"></span>
            </tr>
            <tr v-if=" firstLoad == 0 " >
                <h4 class="mt-3">please select a date and department.</h4>
            </tr>
            <div class="row mt-1 table-border" v-for="pat in filteredPatients" :key="pat.hpercode" style="border-bottom: 1px solid lightgray">
                <div v-show="allDept == 1" class="col-md-2">{{pat.tsdesc}}</div>
                <div class="col-md-1">
                    <span class="font-weight-bold text-success" v-if="pat.isReg!=1"><i class="fa fa-user-friends"></i> F2F</span>
                    <span class="font-weight-bold text-info" v-else-if="pat.isReg==1"><i class="fa fa-mobile-alt fa-lg"></i> Tele</span>
                    <span class="font-weight-bold" v-else></span>
                </div>
                <div class="col-md-1">{{pat.ff_date | filterTime}}</div>
                <div class="col-md-1">{{pat.hpercode}}</div>
                <div class="col-md-2 font-weight-bold">{{pat.fullname}}</div>
                <div class="col-md-2">
                    <span v-if="pat.pattel" class="font-weight-bold text-primary">
                        <a href="#" @click="pop_upEMR(pat.enccode)" target="_blank" rel="noopener noreferrer">{{pat.pattel}}</a>
                    </span>
                    <span v-else class="text-muted">Not provided</span>
                </div>
                <div class="col-md-2">{{pat.scheduledby}}</div>
            </div>
        </div>
    </div>
</template>

<script>

import moment from 'moment'

export default {
    data(){
        return{
            httpAdd: 'http://192.168.6.172/sms?enccode=',
            enccode: '',
            queue: '',
            department: '',
            date_now: '',
            fullname: '',
            direction: '',
            dateNow: '',
            filterByDate: '',
            departments: [],
            filteredPatients: [],
            sel_dept: '',
            allDept: 0,
            firstLoad: 0,

        }
    },
    methods: {

        pop_upEMR(enccode){
            //window pop
            let w = 1224;
            let h = 768;
            let left = (screen.width/2)-(w/2);
            let top = (screen.height/2)-(h/2)-50;
            window.open("http://192.168.6.172/sms?enccode="+enccode, "SMSnotification", "height="+h+", width="+w+", status=yes, toolbar=no, menubar=no, location=no,addressbar=no,top="+top+", left="+left);
        },
        showSMSnotifier(){
                $('#mod_bydate').modal('show')
        },

        clearAll(){

                this.departments = '';
                this.filteredPatients = '';
                this.sel_dept = '';
        },
    },

    created() {

    },
    watch: {
        filterByDate(val){
            const x = this;
            axios.post('api/getByDate').then(d=>{
                console.log(d.data);
                x.sel_dept = '';
                x.departments = d.data.all_dept;

                x.filteredPatients = d.data.byDate;

                // this.filteredPatients = d.data.byDate;
            });
        },
        sel_dept(val){
            console.log(val);
            const x = this;

            if(val=='all'){
                x.allDept = 1;
                x.firstLoad = 1;
                x.filteredPatients = '';

                axios.post('api/getall', {
                    date: this.filterByDate,
                }).then(d=>{
                    x.departments = d.data.all_dept;
                    x.filteredPatients = d.data.byDate;
                });
            }else{
                x.allDept = 0;
                x.firstLoad = 1;
                x.filteredPatients = '';

                axios.post('api/getByDate', {
                    date: this.filterByDate,
                    dept: val
                }).then(d=>{
                    // console.log(d.data.byDate);
                    x.departments = d.data.all_dept;
                    x.filteredPatients = d.data.byDate;
                });
            }
        }
    },

    filters: {
        filterTime: function (time) {
            return moment(time).format('h:mm a');
        }
    }

}
</script>
